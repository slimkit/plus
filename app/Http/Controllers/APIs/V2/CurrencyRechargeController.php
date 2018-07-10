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

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Requests\API2\StoreCurrencyRecharge;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;
use Zhiyi\Plus\Packages\Currency\Processes\Recharge as RechargeProcess;

class CurrencyRechargeController extends Controller
{
    /**
     * 钱包流水.
     *
     * @param Request $request
     * @param CurrencyOrderModel $currencyOrder
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, CurrencyOrderModel $currencyOrder)
    {
        $user = $request->user();

        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $action = $request->query('action');
        $type = $request->query('type');

        $orders = $currencyOrder->where('owner_id', $user->id)
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->when(in_array($action, ['recharge', 'cash']), function ($query) use ($action) {
                return $query->where('target_type', $action);
            })
            ->when(in_array($type, [1, -1]), function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($orders, 200);
    }

    /**
     * 发起充值订单.
     *
     * @param StoreCurrencyRecharge $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(StoreCurrencyRecharge $request)
    {
        $user = $request->user();
        $amount = $request->input('amount');
        $extra = $request->input('extra', []);
        $type = $request->input('type');

        $recharge = new RechargeProcess();

        if (($result = $recharge->createPingPPOrder((int) $user->id, (int) $amount, $type, $extra)) !== false) {
            return response()->json($result, 201);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }

    /**
     * 充值回调通知.
     *
     * @param Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function webhook(Request $request)
    {
        $webhook = new RechargeProcess();
        if ($webhook->webhook($request) === true) {
            return response('通知成功');
        }

        return response('操作失败', 500);
    }

    /**
     * 主动取回凭据.
     *
     * @param CurrencyOrderModel &$currencyOrder
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function retrieve(CurrencyOrderModel $order)
    {
        $retrieve = new RechargeProcess();
        if ($retrieve->retrieve($order) === true) {
            return response()->json($order, 200);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }
}
