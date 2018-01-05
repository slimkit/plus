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
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TypeManager;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Http\Requests\API2\NewStoreWalletRecharge;

class NewWalletRechargeController extends Controller
{
    /**
     * 创建充值订单.
     *
     * @param NewStoreWalletRecharge $request
     * @param TypeManager $manager
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(NewStoreWalletRecharge $request, TypeManager $manager)
    {
        $user = $request->user();
        $amount = $request->input('amount');
        $extra = $request->input('extra');
        $type = $request->input('type');

        if (($result = $manager->driver(Order::TARGET_TYPE_RECHARGE_PING_P_P)->create($user, $amount, $type, $extra)) !== false) {
            return response()->json($result, 201);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }

    /**
     * 充值回调通知.
     *
     * @param Request $request
     * @param TypeManager $manager
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function webhook(Request $request, TypeManager $manager)
    {
        if (($result = $manager->driver(Order::TARGET_TYPE_RECHARGE_PING_P_P)->webhook($request)) === true) {
            return response('通知成功');
        }

        return response('操作失败', 500);
    }

    /**
     * 主动取回凭据.
     *
     * @param WalletOrderModel &$walletOrder
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function retrieve(WalletOrderModel &$walletOrder)
    {
        if (($result = $manager->driver(Order::TARGET_TYPE_RECHARGE_PING_P_P)->retrieve($walletOrder)) === true) {
            return response()->json($walletOrder, 200);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }
}
