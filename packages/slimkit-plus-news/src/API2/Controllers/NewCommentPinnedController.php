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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned as NewsPinnedModel;

class NewCommentPinnedController extends Controller
{
    /**
     * 通过评论置顶.
     *
     * @param Request $request
     * @param ResponseContract $response
     * @param Carbon $dateTime
     * @param NewsModel $news
     * @param CommentModel $comment
     * @param NewsPinnedModel $pinned
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function accept(
        Request $request,
        ResponseContract $response,
        Carbon $dateTime,
        NewsModel $news,
        CommentModel $comment,
        NewsPinnedModel $pinned
    ) {
        $user = $request->user();
        if ($user->id !== $news->user_id) {
            return $response->json(['message' => '你没有权限操作'], 403);
        } elseif ($pinned->expires_at) {
            return $response->json(['message' => '已操作，请勿重复发起'], 422);
        }

        // 设置置顶时间
        $pinned->expires_at = $dateTime->addDay($pinned->day);
        $pinned->state = 1; // 审核通过

        $process = new UserProcess();
        $order = $process->receivables($user->id, $pinned->amount, $pinned->user_id, '置顶资讯评论', sprintf('置顶评论《%s》', str_limit($comment->body, 100, '...')));

        if ($order) {
            $pinned->save();

            $message = sprintf('你在资讯《%s》中申请的评论置顶已被通过', $news->title);
            $comment->user->sendNotifyMessage('news:pinned-comment', $message, [
                'news' => $news,
                'user' => $user,
                'comment' => $comment,
                'pinned' => $pinned,
            ]);

            // 获取发起置顶申请的用户未读系统通知数量
            $unreadCount = $comment->user
                ->unreadNotifications()
                ->count();
            $userCount = UserCountModel::firstOrNew([
                'user_id' => $pinned->user_id,
                'type' => 'user-system',
            ]);
            $userCount->total = $unreadCount;
            $userCount->save();

            // 资讯所有者的资讯评论置顶申请未读数更新
            $unreadCount = $pinned->newQuery()
                ->where('channel', 'news:comment')
                ->where('target_user', $user->id)
                ->whereNull('expires_at')
                ->count();

            $userCount = $userCount->newQuery()
                ->firstOrNew([
                    'user_id' => $user->id,
                    'type' => 'user-news-comment-pinned',
                ]);
            $userCount->total = $unreadCount;
            $userCount->save();

            return $response->json(['message' => '置顶成功'], 201);
        }

        return $response->json(['message' => '操作失败'], 500);
    }

    /**
     * 拒绝评论置顶.
     *
     * @param Request $request
     * @param NewsModel $news
     * @param CommentModel $comment
     * @param NewsPinnedModel $pinned
     * @param ResponseContract $response
     * @param Carbon $dateTime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function reject(
        Request $request,
        NewsModel $news,
        CommentModel $comment,
        NewsPinnedModel $pinned,
        ResponseContract $response,
        Carbon $dateTime
    ) {
        $user = $request->user();
        if ($user->id !== $pinned->target_user || $pinned->channel !== 'news:comment') {
            return $response->json(['message' => '无效操作'], 422);
        } elseif ($pinned->expires_at) {
            return $response->json(['message' => '已被处理'], 422);
        }

        $pinned->load(['comment']);

        $process = new UserProcess();
        $order = $process->reject($user->id, $pinned->amount, $pinned->user_id, '被拒资讯评论置顶', sprintf('被拒资讯评论《%s》申请，退还申请金额', str_limit($pinned->comment->body ?? 'null', 100, '...')));

        if ($order) {
            $pinned->expires_at = $dateTime;
            $pinned->state = 2;
            $pinned->save();

            $message = sprintf('你在资讯《%s》中申请的评论置顶已被驳回', $news->title);
            $comment->user->sendNotifyMessage('news:pinned-comment', $message, [
                'news' => $news,
                'user' => $user,
                'comment' => $comment,
                'pinned' => $pinned,
            ]);

            // 获取发起置顶申请的用户未读系统通知数量
            $unreadCount = $comment->user
                ->unreadNotifications()
                ->count();
            $userCount = UserCountModel::firstOrNew([
                'user_id' => $pinned->user_id,
                'type' => 'user-system',
            ]);
            $userCount->total = $unreadCount;
            $userCount->save();

            // 资讯所有者的资讯评论置顶申请未读数更新
            $unreadCount = $pinned->newQuery()
                ->where('channel', 'news:comment')
                ->where('target_user', $user->id)
                ->whereNull('expires_at')
                ->count();
            $userCount = $userCount->newQuery()
                ->firstOrNew([
                    'user_id' => $user->id,
                    'type' => 'user-news-comment-pinned',
                ]);
            $userCount->total = $unreadCount;
            $userCount->save();

            return $response->json(null, 204);
        }

        return $response->json(['message' => '操作失败'], 500);
    }
}
