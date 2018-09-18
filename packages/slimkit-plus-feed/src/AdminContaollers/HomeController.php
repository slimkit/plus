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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminControllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Repository\WalletRatio;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned;

class HomeController extends Controller
{
    /**
     * feed management background entry.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(WalletRatio $walletRatioRepository)
    {
        return view('feed:view::admin', [
            'base_url' => route('feed:admin'),
            'csrf_token' => csrf_token(),
            'wallet_ratio' => $walletRatioRepository->get(),
        ]);
    }

    /**
     * 获取分享统计信息.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function statistics(Request $request, Feed $feedModel, Comment $commentModel, Carbon $datetime)
    {
        $type = $request->query('type', 'all');
        // $now = $datetime->now();
        $feedPinned = $commentPinned = new FeedPinned();
        switch ($type) {
            // 查询总统计信息
            case 'all':

                break;
            // 查询今日统计
            case 'today':
                $datetime = $datetime->today();
                $feedModel = $feedModel->where('created_at', '>', $datetime);
                $commentModel = $commentModel->where('created_at', '>', $datetime);
                $feedPinned = $feedPinned->where('created_at', '>', $datetime);
                $commentPinned = $commentPinned->where('created_at', '>', $datetime);
                break;
            // 查询昨日统计
            case 'yesterday':
                $stime = $datetime->yesterday();
                $etime = $datetime->today();
                $feedModel = $feedModel->whereBetween('created_at', [$stime, $etime]);
                $commentModel = $commentModel->whereBetween('created_at', [$stime, $etime]);
                $feedPinned = $feedPinned->whereBetween('created_at', [$stime, $etime]);
                $commentPinned = $commentPinned->whereBetween('created_at', [$stime, $etime]);
                break;
            // 查询一周统计
            case 'week':
                $datetime = $datetime->subDays(7);
                $feedModel = $feedModel->where('created_at', '>', $datetime);
                $commentModel = $commentModel->where('created_at', '>', $datetime);
                $feedPinned = $feedPinned->where('created_at', '>', $datetime);
                $commentPinned = $commentPinned->where('created_at', '>', $datetime);
                break;
        }

        // 动态总数
        $feedsCount = $feedModel->count();

        // 动态评论总数
        $commentsCount = $commentModel->where('commentable_type', 'feeds')->count();

        // $feedPinned = $feedPinned->whereDate('expires_at', '>=', $now)->count();

        // 置顶动态
        $feedPinnedCount = $feedPinned
            ->where('channel', 'feed')
            ->count();

        $commentPinnedCount = $commentPinned->where('channel', 'comment')->count();

        // 付费动态总数
        $payFeedsCount = $feedModel->whereExists(function ($query) {
            return $query->from('paid_nodes')->where('channel', 'feed')->whereRaw('paid_nodes.raw = feeds.id');
        })
        // ->orWhere(function ($query) {
        //     return $query->whereHas('images', function ($query) {
        //         return $query->whereExists(function ($query) {
        //             return $query->from('paid_nodes')->where('channel', 'file')->whereRaw('paid_nodes.raw = file_withs.id');
        //         });
        //     });
        // })
        ->count();

        // 付费总金额
        // TODO 目前只统计文字付费动态金额
        $payCount = $feedModel->whereExists(function ($query) {
            return $query->from('paid_nodes')->where('channel', 'feed')->whereRaw('paid_nodes.raw = feeds.id');
        })->get()->map(function ($feed) {
            return $feed->paidNode->amount;
        })->sum();

        // 付费总人数
        // TODO 同上

        $status = [];
        $status = config('feed');

        return response()->json([
            'feedsCount' => $feedsCount,
            'commentsCount' => $commentsCount,
            'payFeedsCount' => $payFeedsCount,
            'payCount' => $payCount,
            'topFeed' => $feedPinnedCount,
            'topComment' => $commentPinnedCount,
            'status' => $status,
        ])->setStatusCode(200);
    }

    /**
     * 关闭应用.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function handleComponentStatus(Request $request, Configuration $configuration)
    {
        $open = $request->input('open');

        $configuration->set('feed.open', $open);

        return response()->json(['message' => '设置成功'])->setStatusCode(201);
    }

    /**
     * 关闭应用打赏.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function handleRewardStatus(Request $request, Configuration $configuration)
    {
        $reward = $request->input('reward');

        $configuration->set('feed.reward', $reward);

        return response()->json(['message' => '设置成功'])->setStatusCode(201);
    }
}
