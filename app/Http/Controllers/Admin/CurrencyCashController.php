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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CurrencyOrder as OrderModel;

class CurrencyCashController extends Controller
{
    /**
     * 提现列表.
     *
     * @param Request $request
     * @param OrderModel $orderModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function list(Request $request, OrderModel $orderModel)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $state = (int) $request->query('state');
        $user = (int) $request->query('user');
        $name = $request->query('name');
        $cashes = $orderModel->where('target_type', 'cash')
        ->when(in_array($state, [-1, 0, 1]), function ($query) use ($state) {
            return $query->where('state', $state);
        })
        ->when($user, function ($query) use ($user) {
            return $query->where('user_id', $user);
        })
        ->when($name, function ($query) use ($name) {
            return $query->whereHas('user', function ($query) use ($name) {
                return $query->where('name', 'like', '%'.$name);
            });
        })
        ->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })
        ->limit($limit)
        ->orderBy('id', 'desc')
        ->with('user')
        ->get();

        return response()->json($cashes, 200);
    }
}
