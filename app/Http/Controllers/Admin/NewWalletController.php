<?php

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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\WalletOrder;

class NewWalletController extends Controller
{
    /**
     * 新版钱包统计.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        $expenditure = WalletOrder::query()
            ->where('type', -1)
            ->where('state', 1)
            ->select(DB::raw('count(id) as count, sum(amount) as sum'))
            ->first();
        $income = WalletOrder::query()
            ->where('type', 1)
            ->where('state', 1)
            ->select(DB::raw('count(id) as count, sum(amount) as sum'))->first();

        return response()->json([
            'expenditure' => $expenditure,
            'income'      => $income,
        ], 200);
    }

    /**
     * 新版钱包流水.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function waters(Request $request)
    {
        $user = $request->query('user');
        $state = $request->query('state');

        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);

        $query = (new WalletOrder)->newQuery();

        $query->when($user, function ($query) use ($user) {
            return $query->where('owner_id', $user);
        })
            ->when($state, function ($query) use ($state) {
                return $query->where('state', $state);
            });

        $count = $query->count();
        $items = $query->limit($limit)->offset($offset)->latest()->get();

        return response()->json($items, 200, ['x-total' => $count]);
    }
}
