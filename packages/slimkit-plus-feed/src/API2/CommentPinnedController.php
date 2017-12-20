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
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned as FeedPinnedModel;

class CommentPinnedController extends Controller
{
    /**
     * 获取动态评论当前用户审核列表.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, FeedPinnedModel $model)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = $request->query('after');

        $pinneds = $model->with('comment')
            ->where('channel', 'comment')
            ->where('target_user', $user->id)
            ->when(boolval($after), function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        $pinneds->load('feed');

        return response()->json($pinneds, 200);
    }

    /**
     * 固定评论.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Carbon\Carbon $dateTime
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned $pinned
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function pass(Request $request,
                         ResponseContract $response,
                         Carbon $dateTime,
                         WalletChargeModel $charge,
                         FeedModel $feed,
                         CommentModel $comment,
                         FeedPinnedModel $pinned)
    {
        $user = $request->user();

        if ($user->id !== $feed->user_id) {
            return $response->json(['message' => ['你没有权限操作']], 403);
        } elseif ($pinned->expires_at) {
            return $response->json(['message' => ['已操作，请勿重复发起']], 422);
        }

        $pinned->expires_at = $dateTime->addDay($pinned->day);

        // 动态发起人增加收款凭据
        $charge->user_id = $user->id;
        $charge->channel = 'user';
        $charge->account = $pinned->user_id;
        $charge->action = 1;
        $charge->amount = $pinned->amount;
        $charge->subject = '置顶动态评论';
        $charge->body = sprintf('置顶评论《%s》', str_limit($comment->body, 100, '...'));
        $charge->status = 1;

        return $feed->getConnection()->transaction(function () use ($response, $pinned, $comment, $user, $charge) {
            $pinned->save();
            $comment->save();
            $user->wallet()->increment('balance', $charge->amount);
            $user->walletCharges()->save($charge);

            $pinned->user->sendNotifyMessage('feed-comment:pass', '你申请置顶的动态评论已被通过', [
                'comment' => $comment,
                'pinned' => $pinned,
            ]);

            return $response->json(['message' => ['置顶成功']], 201);
        });
    }

    public function reject(Request $request,
                           ResponseContract $response,
                           Carbon $dateTime,
                           WalletChargeModel $charge,
                           FeedPinnedModel $pinned)
    {
        $user = $request->user();

        if ($user->id !== $pinned->target_user || $pinned->channel !== 'comment') {
            return $response->json(['message' => ['无效操作']], 422);
        } elseif ($pinned->expires_at) {
            return $response->json(['message' => ['已被处理']], 422);
        }

        $pinned->load(['comment']);

        // 拒绝凭据
        $charge->user_id = $pinned->user_id;
        $charge->channel = 'user';
        $charge->account = $user->id;
        $charge->action = 1;
        $charge->amount = $pinned->amount;
        $charge->subject = '被拒动态评论置顶';
        $charge->body = sprintf('被拒动态评论《%s》申请，退还申请金额', str_limit($pinned->comment->body ?? 'null', 100, '...'));
        $charge->status = 1;

        return $pinned->getConnection()->transaction(function () use ($response, $charge, $pinned, $dateTime) {
            $charge->save();
            $pinned->user->wallet()->increment('balance', $pinned->amount);
            $pinned->expires_at = $dateTime;
            $pinned->save();

            $pinned->user->sendNotifyMessage('feed-comment:reject', '你申请置顶的动态评论已被驳回', [
                'comment' => $pinned->comment,
                'pinned' => $pinned,
            ]);

            return $response->json(null, 204);
        });
    }

    /**
     * 取消置顶.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Carbon\Carbon $dateTime
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function delete(Request $request,
                           ResponseContract $response,
                           Carbon $dateTime,
                           FeedModel $feed,
                           CommentModel $comment)
    {
        $user = $request->user();
        $pinned = $feed->pinnedComments()->newPivotStatementForId($comment->id)->first();

        if ($user->id !== $feed->user_id) {
            return $response->json(['message' => ['你没有权限操作']], 403);
        } elseif (! $pinned) {
            return $response->json(['message' => ['无效操作']], 422);
        }

        $pinned->expires_at = $dateTime;

        return $pinned->save()
            ? $response->make('', 204)
            : $response->json(['message' => ['操作失败']])->setStatusCode(500);
    }
}
