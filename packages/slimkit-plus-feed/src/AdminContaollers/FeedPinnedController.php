<?php

declare(strict_types=1);

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminControllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned;

class FeedPinnedController extends Controller
{
    public function index(Request $request, FeedPinned $pinned)
    {
        $limit = $request->query('limit', 20);
        $after = $request->query('after', 0);
        $pinneds = $pinned->where('channel', 'feed')->with('feed')
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->limit($limit)
            ->get();

        return response()->json($pinneds)->setStatusCode(200);
    }

    public function audit(Request $request, FeedPinned $pinned, Carbon $datetime)
    {
        $action = $request->input('action', 'accept');

        if (! in_array($action, ['accept', 'reject'])) {
            abort(404);
        }

        if ($pinned->expires_at !== null) {
            return response()->json(['message' => ['该记录已被处理']], 403);
        }

        return $this->{$action}($pinned, $datetime);
    }

    public function accept(FeedPinned $pinned, Carbon $datetime)
    {
        $pinned->expires_at = $datetime->addDay($pinned->day)->toDateTimeString();

        $pinned->save();
        $pinned->user->sendNotifyMessage('feeds:pinned:accept', sprintf('你申请的动态《%s》已被置顶', str_limit($pinned->feed->feed_content, 100)), [
            'feed' => $pinned->feed,
            'pinned' => $pinned,
        ]);

        return response()->json($pinned, 201);
    }

    // 拒绝动态置顶申请
    public function reject(FeedPinned $pinned)
    {
        $charge = new WalletChargeModel();
        $charge->user_id = $pinned->user_id;
        $charge->channel = 'system';
        $charge->action = 1;
        $charge->amount = $pinned->amount;
        $charge->subject = '退还动态置顶申请费用';
        $charge->body = sprintf('退还动态《%s》的置顶申请费用', str_limit($pinned->feed->feed_content, 100));
        $charge->status = 1;

        $pinned->getConnection()->transaction(function () use ($pinned, $charge) {
            $pinned->delete();
            $charge->save();

            $pinned->user->sendNotifyMessage('news:pinned:reject', sprintf('动态《%s》的置顶申请已被驳回', str_limit($pinned->feed->feed_content, 100)), [
                'feed' => $pinned->feed,
                'pinned' => $pinned,
            ]);
        });

        return response()->json([], 204);
    }

    public function set(Request $request, Feed $feed, Carbon $datetime)
    {
        $time = intval($request->input('day'));
        $pinned = $request->input('pinned');
        if (! $pinned) {
            $datetime = $datetime->addDay($time);
            $pinned = new FeedPinned();
            $pinned->user_id = $feed->user_id;
            $pinned->target = $feed->id;
            $pinned->channel = 'feed';
            $pinned->target_user = 0;
            $pinned->amount = 0;
            $pinned->day = $datetime->diffInDays(Carbon::now());
            $pinned->expires_at = $datetime->toDateTimeString();

            $pinned->save();
            $pinned->user->sendNotifyMessage('feed:pinned:accept', sprintf('你的动态《%s》已被管理员设置为置顶', str_limit($pinned->feed->feed_content, 100)), [
                'feed' => $feed,
                'pinned' => $pinned,
            ]);

            return response()->json(['message' => ['操作成功'], 'data' => $pinned], 201);
        } else {
            $pinned = FeedPinned::find($pinned);
            $date = new Carbon($pinned->expires_at);
            $datetime = $date->addDay($time);
            $pinned->day = $datetime->diffInDays(Carbon::now());
            $pinned->expires_at = $datetime->toDateTimeString();
            $pinned->save();

            $pinned->user->sendNotifyMessage('feed:pinned:accept', sprintf('你的动态《%s》已被管理员设置为置顶', str_limit($pinned->feed->feed_content, 100)), [
                'feed' => $feed,
                'pinned' => $pinned,
            ]);

            return response()->json(['message' => ['操作成功'], 'data' => $pinned], 201);
        }
    }

    /**
     * 撤销置顶.
     * @param  Request    $request [description]
     * @param  Feed       $feed    [description]
     * @param  FeedPinned $pinned  [description]
     * @return [type]              [description]
     */
    public function destroy(Feed $feed, FeedPinned $pinned)
    {
        $pinned->where('target', $feed->id)
            ->where('channel', 'feed')
            ->delete();

        return response()->json([], 204);
    }
}
