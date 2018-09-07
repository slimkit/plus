<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Services\Push;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\AtMessage\AtMessageHelperTrait;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedComment as CommentFormRequest;

class FeedCommentController extends Controller
{
    use AtMessageHelperTrait;

    /**
     * List comments of the feed.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, FeedModel $feed)
    {
        $user = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 15);
        $after = $request->query('after', false);

        $comments = $feed->comments()
            ->whereDoesntHave('blacks', function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->with([
                'user' => function ($query) {
                    return $query->withTrashed();
                },
                'reply',
            ])
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json([
            'pinneds' => ! $after ? app()->call([$this, 'pinneds'], ['feed' => $feed]) : [],
            'comments' => $comments,
        ])->setStatusCode(200);
    }

    public function pinneds(Request $request, Carbon $dateTime, FeedModel $feed)
    {
        if ($request->query('after')) {
            return [];
        }

        return $feed->pinnedComments()
            ->with(['user' => function ($query) {
                return $query->withTrashed();
            }, 'reply'])
            ->where('expires_at', '>', $dateTime)
            ->orderBy('amount', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get a comment.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param mixed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ResponseFactoryContract $response, $feed, CommentModel $comment)
    {
        unset($feed);

        return $response->json($comment, 200);
    }

    /**
     * destroy the comment.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(Request $request, ResponseFactoryContract $response, FeedModel $feed, CommentModel $comment)
    {
        $user = $request->user();
        if ($comment->user_id !== $user->id) {
            return $response->json(['message' => '没有权限'], 403);
        }
        $pinnedComment = FeedPinned::whereNull('expires_at')
            ->where('target', $comment->id)
            ->where('user_id', $user->id)
            ->first();
        $feed->getConnection()->transaction(function () use ($user, $feed, $comment, $pinnedComment) {
            if ($pinnedComment) {
                $pinnedComment->delete();
                $userUnredCount = $pinnedComment->newQuery()
                    ->whereNull('expires_at')
                    ->where('target_user', $feed->user_id)
                    ->where('channel', 'comment')
                    ->count();
                $process = new UserProcess();
                $process->reject(0, $pinnedComment->amount, $user->id, '评论申请置顶退款', sprintf('退还在动态《%s》申请置顶的评论的款项', str_limit($feed->feed_content, 100)));
                $userCount = UserCountModel::firstOrNew([
                    'user_id' => $feed->user_id,
                    'type' => 'user-feed-comment-pinned',
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
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedComment $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
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

        $feed->getConnection()->transaction(function () use ($feed, $user, $comment) {
            $feed->comments()->save($comment);
            $feed->increment('feed_comment_count', 1);
            $user->extra()->firstOrCreate([])->increment('comments_count', 1);
            if ($feed->user->id !== $user->id) {
                // 添加被评论未读数
                // 旧, 保留
                $feed->user->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
                // 新, 1.8启用
                $userCommentedCount = UserCountModel::firstOrNew([
                    'type' => 'user-commented',
                    'user_id' => $feed->user->id,
                ]);

                $userCommentedCount->total += 1;
                $userCommentedCount->save();
                // 推送
                app(Push::class)->push(sprintf('%s 评论了你的动态', $user->name), (string) $feed->user->id, ['channel' => 'feed:comment']);
                unset($userCommentedCount);
            }
        });

        if ($replyUser && $replyUser !== $user->id && $replyUser !== $feed->user_id) {
            $replyUser = $user->newQuery()->where('id', $replyUser)->first();
            // 添加被回复未读数
            // 旧, 暂时保留
            $replyUser->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
            // 新, 1.8启用
            $userCommentedCount = UserCountModel::firstOrNew([
                'type' => 'user-commented',
                'user_id' => $replyUser->id,
            ]);

            $userCommentedCount->total += 1;
            $userCommentedCount->save();
            // 推送
            app(Push::class)->push(sprintf('%s 回复了你的评论', $user->name), (string) $replyUser->id, ['channel' => 'feed:comment-reply']);
            unset($userCommentedCount);
        }
        $comment->load('user');

        // 发送 at 数据
        $this->sendAtMessage($comment->body, $user, $comment);

        return $response->json([
            'message' => '操作成功',
            'comment' => $comment,
        ])->setStatusCode(201);
    }
}
