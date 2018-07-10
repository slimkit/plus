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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Http\Requests\API2\StoreCurrencyCash;
use Zhiyi\Plus\Packages\Currency\Processes\Cash as CashProcess;

class CurrencyCashController extends Controller
{
    /**
     * 发起提现订单.
     *
     * @param StoreCurrencyCash $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(StoreCurrencyCash $request)
    {
        $user = $request->user();
        $amount = $request->input('amount');

        $cash = new CashProcess();

        if ($cash->createOrder($user->id, (int) $amount) !== false) {
            return response()->json(['message' => ['积分提取申请已提交，请等待审核']], 201);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }
}
