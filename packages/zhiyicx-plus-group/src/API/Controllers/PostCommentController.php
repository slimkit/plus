<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\PlusGroup\API\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Services\Push;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\PlusGroup\Models\Pinned;
use Zhiyi\PlusGroup\Models\Post as GroupPostModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\PlusGroup\API\Requests\StoreComment as StoreCommentRequest;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class PostCommentController
{
    /**
     * get list of comments.
     *
     * @param Request $request
     * @param GroupPostModel $post
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function get(Request $request, GroupPostModel $post)
    {
        $user = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $datas = $post->comments()
            ->whereDoesntHave('blacks', function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
        ->with(['user', 'reply'])
        ->limit($limit)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json([
            'pinneds' => ! $after ? app()->call([$this, 'pinneds'], ['post' => $post]) : collect([]),
            'comments' => $datas,
        ], 200);
    }

    /**
     * get pinned comment of a post.
     *
     * @param Carbon $datetime
     * @param GroupPostModel $post
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function pinneds(Carbon $datetime, GroupPostModel $post)
    {
        return $post->comments()->whereExists(function ($query) use ($post, $datetime) {
                return $query->from('group_pinneds')->whereRaw('group_pinneds.target = comments.id')
                    ->where('group_pinneds.raw', $post->id)
                    ->where('expires_at', '>', $datetime);
        })
            ->join('group_pinneds', function ($join) use ($datetime) {
                return $join->on('group_pinneds.target', '=', 'comments.id')
                    ->where('group_pinneds.raw', '>', 0);
            })
            ->select('comments.*')
            ->orderBy('group_pinneds.amount', 'desc')
            ->orderBy('group_pinneds.created_at', 'desc')
            ->with('user')
            ->get();
    }

    /**
     * store a comment for a group post.
     *
     * @param StoreCommentRequest $request
     * @param GroupPostModel      $post
     * @param CommentModel        $commentModel
     * @return mixed
     * @throws \Throwable
     * @author BS <414606094@qq.com>
     */
    public function store(StoreCommentRequest $request, GroupPostModel $post, CommentModel $commentModel)
    {
        $body = $request->input('body');
        $reply = $request->input('reply_user', 0);
        $user = $request->user();
        $mark = $request->input('comment_mark', '');

        $group = $post->group;
        $member = $group->members()->where('user_id', $user->id)->where('audit', 1)->first();
        if ($member && $member->disabled == 1) {
            return response()->json(['message' => '您已被该圈子拉黑，无法发送评论'], 403);
        }

        if ($group->model != 'public' && ! $member) {
            return response()->json(['message' => '您没有评论权限'], 403);
        }

        $commentModel->user_id = $user->id;
        $commentModel->target_user = $post->user_id;
        $commentModel->reply_user = $reply;
        $commentModel->body = $body;
        $commentModel->comment_mark = $mark;

        $post->getConnection()->transaction(function () use ($post, $commentModel, $reply, $user) {
            $post->comments()->save($commentModel);
            $post->increment('comments_count', 1);
            $user->extra()->firstOrCreate([])->increment('comments_count', 1);

            if ($post->user_id !== $user->id) {
                $post->user->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
                // 1.8启用, 新版未读消息提醒
                $userCount = UserCountModel::firstOrNew([
                    'type' => 'user-commented',
                    'user_id' => $post->user_id
                ]);
                $userCount->total += 1;
                app(Push::class)->push(sprintf('%s评论了你的帖子', $user->name), (string) $post->user->id, ['channel' => 'group:comment']);
                $userCount->save();
            }

            if ($reply && $reply !== $user->id && $reply !== $post->user_id) {
                // 1.8启用, 新版未读消息提醒
                $userCount = UserCountModel::firstOrNew([
                    'type' => 'user-commented',
                    'user_id' => $reply
                ]);
                $userCount->total += 1;
                $replyUser = app(UserModel::class)->find($reply);
                $replyUser->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
                app(Push::class)->push(sprintf('%s回复了你的评论', $user->name), (string) $replyUser->id, ['channel' => 'group:comment-reply']);
                $userCount->save();
            }

            $commentModel->load(['user', 'reply', 'target']);
        });

        return response()->json([
            'message' => ['操作成功'],
            'comment' => $commentModel,
        ], 201);
    }

    /**
     * delete a comment.
     *
     * @param Request        $request
     * @param GroupPostModel $post
     * @param CommentModel   $comment
     * @return mixed
     * @throws \Throwable
     * @author BS <414606094@qq.com>
     */
    public function delete(Request $request, GroupPostModel $post, CommentModel $comment)
    {
        $user = $request->user();
        if ($user->id !== $comment->user_id && ! $post->group->isManager($user)) {
            return response()->json(['message' => '没有权限'], 403);
        }
        $pinned = Pinned::where('user_id', $user->id)
            ->where('target', $comment->id)
            ->where('channel', 'comment')
            ->first();
        $post->getConnection()->transaction(function () use ($post, $comment, $user, $pinned) {
            if ($pinned) {
                $process = new UserProcess();
                $process->reject($user->id, $pinned->amount, $comment->user_id, '退还帖子内置顶评论申请金额', sprintf('退还帖子《%s》下置顶评论申请的金额', $post->title));
            }
            $post->decrement('comments_count', 1);
            $user->extra()->decrement('comments_count', 1);
            $comment->delete();
        });

        return response()->json()->setStatusCode(204);
    }
}
