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

use Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned as FeedPinnedModel;

class NewCommentPinnedController extends Controller
{
    /**
     * 固定评论.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Carbon\Carbon $dateTime
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned $pinned
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function pass(
        Request $request,
        ResponseContract $response,
        Carbon $dateTime,
        FeedModel $feed,
        CommentModel $comment,
        FeedPinnedModel $pinned
    ) {
        $user = $request->user();

        if ($user->id !== $feed->user_id) {
            return $response->json(['message' => '你没有权限操作'], 403);
        } elseif ($pinned->expires_at) {
            return $response->json(['message' => '已操作，请勿重复发起'], 422);
        }

        if ($pinned->channel !== 'comment') {
            return $response->json(['message' => '参数错误'], 422);
        }

        $pinned->expires_at = $dateTime->addDay($pinned->day);
        // 申请动态置顶的用户, 更新系统消息未读数
        $userUnreadCount = $pinned->user
            ->unreadNotifications()
            ->count();
        $userCount = UserCountModel::firstOrNew([
                'type' => 'user-system',
                'user_id' => $pinned->user_id,
            ]);

        $userCount->total = $userUnreadCount + 1;

        $process = new UserProcess();
        $order = $process->receivables($user->id, $pinned->amount, $pinned->user_id, '置顶动态评论', sprintf('置顶评论“%s”', str_limit($comment->body, 100, '...')));

        if ($order) {
            $pinned->save();

            $pinned->user->sendNotifyMessage('feed-comment:pass', '你申请置顶的动态评论已被通过', [
                'comment' => $comment,
                'pinned' => $pinned,
            ]);
            $userCount->save();
            // 更新当前用户动态置顶评论审核未读数
            $userUnreadCount = $pinned->newQuery()
                ->where('channel', 'comment')
                ->where('target_user', $user->id)
                ->whereNull('expires_at')
                ->count();
            $userCount = $userCount->newQuery()
                ->firstOrNew([
                    'user_id' => $user->id,
                    'type' => 'user-feed-comment-pinned',
                ]);
            $userCount->total = $userUnreadCount;
            $userCount->save();

            return $response->json(['message' => '置顶成功'], 201);
        }

        return $response->json(['message' => '操作失败'], 500);
    }

    /**
     * 拒绝置顶.
     *
     * @param Request $request
     * @param ResponseContract $response
     * @param Carbon $dateTime
     * @param FeedPinnedModel $pinned
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function reject(
        Request $request,
        ResponseContract $response,
        Carbon $dateTime,
        FeedPinnedModel $pinned
    ) {
        $user = $request->user();

        if ($user->id !== $pinned->target_user || $pinned->channel !== 'comment') {
            return $response->json(['message' => '无效操作'], 422);
        } elseif ($pinned->expires_at) {
            return $response->json(['message' => '已被处理'], 422);
        }

        $pinned->load(['comment']);

        // 拒绝凭据
        $process = new UserProcess();
        $order = $process->reject($user->id, $pinned->amount, $pinned->user_id, '被拒动态评论置顶', sprintf('被拒动态评论“%s”申请，退还申请金额', str_limit($pinned->comment->body ?? 'null', 100, '...')));
        // 申请动态置顶的用户, 更新系统消息未读数
        $userUnreadCount = $pinned->user
            ->unreadNotifications()
            ->count();
        $userCount = UserCountModel::firstOrNew([
                'type' => 'user-system',
                'user_id' => $pinned->user_id,
            ]);

        $userCount->total = $userUnreadCount + 1;

        if ($order) {
            $pinned->expires_at = $dateTime;
            $pinned->save();

            $pinned->user->sendNotifyMessage('feed-comment:reject', '你申请置顶的动态评论已被驳回', [
                'comment' => $pinned->comment,
                'pinned' => $pinned,
            ]);
            $userCount->save();

            // 更新动态所有者的动态评论置顶审核未读数
            $userUnreadCount = $pinned->newQuery()
                ->where('target_user', $user->id)
                ->where('channel', 'comment')
                ->whereNull('expires_at')
                ->count();
            Log::debug($userUnreadCount);
            $userCount = UserCountModel::firstOrNew([
                'user_id' => $user->id,
                'type' => 'user-feed-comment-pinned',
            ]);
            $userCount->total = $userUnreadCount;
            Log::debug($userCount);
            $userCount->save();

            return $response->json(null, 204);
        }

        return $response->json(['message' => '操作失败'], 500);
    }
}
