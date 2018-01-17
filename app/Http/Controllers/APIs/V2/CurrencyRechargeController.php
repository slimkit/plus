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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Packages\Currency\Processes\Recharge as RechargeProcess;

class CurrencyRechargeController extends controller
{
    /**
     * 发起充值订单.
     *
     * @param Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $amount = $request->input('amount');
        $extra = $request->input('extra');
        $type = $request->input('type');

        $recharge = new RechargeProcess();

        if (($result = $recharge->createPingPPOrder($user->id, $amount, $type, $extra) !== false)) {
            return response()->json($result, 201);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }
}
