<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Cache;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedComment as CommentFormRequest;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned;
use Zhiyi\Plus\AtMessage\AtMessageHelperTrait;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Plus\Notifications\Comment as CommentNotification;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class FeedCommentController extends Controller
{
    use AtMessageHelperTrait;

    /**
     * List comments of the feed.
     *
     * @param  Request  $request
     * @param  ResponseFactoryContract  $response
     * @param  FeedModel  $feed
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(
        Request $request,
        ResponseFactoryContract $response,
        FeedModel $feed
    ) {
        $user = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 15);
        $after = $request->query('after', false);

        $comments = $feed->comments()
            ->whereDoesntHave('blacks', function (Builder $query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($after, function (Builder $query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json([
            'pinneds'  => ! $after ? app()->call([$this, 'pinneds'],
                ['feed' => $feed]) : [],
            'comments' => $comments,
        ])->setStatusCode(200);
    }

    public function pinneds(Request $request, FeedModel $feed)
    {
        if ($request->query('after')) {
            return [];
        }

        return $feed->pinnedComments()
            ->get();
    }

    /**
     * Get a comment.
     *
     * @param  ResponseFactoryContract  $response
     * @param  mixed  $feed
     * @param  CommentModel  $comment
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(
        ResponseFactoryContract $response,
        $feed,
        CommentModel $comment
    ) {
        unset($feed);

        return $response->json($comment, 200);
    }

    /**
     * destroy the comment.
     *
     * @param  Request  $request
     * @param  ResponseFactoryContract  $response
     * @param  FeedModel  $feed
     * @param  CommentModel  $comment
     *
     * @return mixed
     * @throws \Throwable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(
        Request $request,
        ResponseFactoryContract $response,
        FeedModel $feed,
        CommentModel $comment
    ) {
        $user = $request->user();
        if ($comment->user_id !== $user->id) {
            return $response->json(['message' => '没有权限'], 403);
        }
        $pinnedComment = FeedPinned::whereNull('expires_at')
            ->where('target', $comment->id)
            ->where('user_id', $user->id)
            ->first();
        $feed->getConnection()->transaction(function () use (
            $user,
            $feed,
            $comment,
            $pinnedComment
        ) {
            if ($pinnedComment) {
                $pinnedComment->delete();
                $userUnredCount = $pinnedComment->newQuery()
                    ->whereNull('expires_at')
                    ->where('target_user', $feed->user_id)
                    ->where('channel', 'comment')
                    ->count();
                $process = new UserProcess();
                $process->reject(0, $pinnedComment->amount, $user->id,
                    '评论申请置顶退款', sprintf('退还在动态《%s》申请置顶的评论的款项',
                        Str::limit($feed->feed_content, 100)));
                $userCount = UserCountModel::firstOrNew([
                    'user_id' => $feed->user_id,
                    'type'    => 'user-feed-comment-pinned',
                ]);
                $userCount->total = $userUnredCount;
                $userCount->save();
            }
            $feed->decrement('feed_comment_count', 1);
            $user->extra()->decrement('comments_count', 1);
            $comment->delete();
        });

        return $response->make('', 204);
    }

    /**
     * Send comment of the feed.
     *
     * @param  CommentFormRequest  $request
     * @param  ResponseFactoryContract  $response
     * @param  FeedModel  $feed
     * @param  CommentModel  $comment
     *
     * @return mixed
     * @throws \Throwable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(
        CommentFormRequest $request,
        ResponseFactoryContract $response,
        FeedModel $feed,
        CommentModel $comment
    ) {
        $replyUser = intval($request->input('reply_user', 0));
        $body = $request->input('body');
        $user = $request->user();
        $mark = $request->input('comment_mark', '');

        $comment->user_id = $user->id;
        $comment->reply_user = $replyUser;
        $comment->target_user = $feed->user_id;
        $comment->body = $body;
        $comment->comment_mark = $mark;

        $feed->getConnection()->transaction(function () use (
            $feed,
            $user,
            $comment
        ) {
            $feed->comments()->save($comment);
            $feed->increment('feed_comment_count', 1);
            $user->extra()->firstOrCreate([])->increment('comments_count', 1);
            Cache::forget(sprintf('Feed:%d:comments-preview', $feed->id));
        });

        // 发送消息通知
        if ($feed->user) {
            $feed->user->notify(new CommentNotification($comment, $user));
        }

        // 如果回复条件满足，发送给被回复用户通知
        if ($replyUser && $replyUser !== $user->id
            && $replyUser !== $feed->user_id
        ) {
            $replyUser = $user->newQuery()->where('id', $replyUser)->first();
            if ($replyUser) {
                $replyUser->notify(new CommentNotification($comment, $user));
            }
        }

        // 发送 at 数据
        $this->sendAtMessage($comment->body, $user, $comment);

        return $response->json([
            'message' => '操作成功',
            'comment' => $comment,
        ])->setStatusCode(201);
    }
}
