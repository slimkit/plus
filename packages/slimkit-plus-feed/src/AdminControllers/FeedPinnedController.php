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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminControllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Notifications\System as SystemNotification;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

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
        $pinned->user->notify(new SystemNotification('你申请的动态置顶已通过', [
            'type' => 'pinned:feeds',
            'state' => 'passed',
            'feed' => [
                'id' => $pinned->target,
            ],
        ]));

        return response()->json($pinned, 201);
    }

    // 拒绝动态置顶申请
    public function reject(FeedPinned $pinned, UserProcess $userProcess)
    {
        $body = sprintf(
            '动态《%s》的置顶申请已被驳回，退还%s积分',
            Str::limit($pinned->feed->feed_content, 100),
            $pinned->amount
        );

        $pinned->getConnection()->transaction(function () use ($pinned, $body, $userProcess) {
            $order = $userProcess->receivables(
                $pinned->user_id,
                $pinned->amount,
                $pinned->feed->user_id,
                '退还动态置顶申请费用',
                $body
            );

            if ($order) {
                $pinned->delete();
                $pinned->user->notify(new SystemNotification('你申请的动态置顶未通过', [
                    'type' => 'pinned:feeds',
                    'state' => 'rejected',
                    'feed' => [
                        'id' => $pinned->target,
                    ],
                ]));
            }
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
        } else {
            $pinned = FeedPinned::find($pinned);
            $date = new Carbon($pinned->expires_at);
            $datetime = $date->addDay($time);
            $pinned->day = $datetime->diffInDays(Carbon::now());
            $pinned->expires_at = $datetime->toDateTimeString();
        }

        $pinned->save();

        $pinned->user->notify(new SystemNotification('你的动态被管理员设置为置顶', [
            'type' => 'pinned:feeds',
            'state' => 'admin',
            'feed' => [
                'id' => $pinned->target,
            ],
        ]));

        return response()->json(['message' => ['操作成功'], 'data' => $pinned], 201);
    }

    /**
     * 撤销置顶.
     * @param Feed $feed [description]
     * @param FeedPinned $pinned [description]
     * @return JsonResponse [type]              [description]
     */
    public function destroy(Feed $feed, FeedPinned $pinned)
    {
        $pinned->newQuery()->where('target', $feed->id)
            ->where('channel', 'feed')
            ->delete();

        return response()->json([], 204);
    }
}
