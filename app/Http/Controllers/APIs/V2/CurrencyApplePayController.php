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
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Requests\API2\StoreCurrencyRecharge;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;
use Zhiyi\Plus\Packages\Currency\Processes\AppStorePay as AppStorePayProcess;

class CurrencyApplePayController extends Controller
{
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

        $recharge = new AppStorePayProcess();

        if (($result = $recharge->createOrder((int) $user->id, (int) $amount)) !== false) {
            return response()->json($result, 201);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }

    /**
     * 主动取回凭据.
     *
     * @param Request $request
     * @param CurrencyOrderModel &$currencyOrder
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function retrieve(Request $request, CurrencyOrderModel $currencyOrder)
    {
        $receipt = $request->input('receipt');
        if (! is_array($receipt)) {
            return response()->json(['message' => ['参数错误']], 422);
        }
        $retrieve = new AppStorePayProcess();
        if (($result = $retrieve->verifyReceipt($receipt, $currencyOrder)) === true) {
            return response()->json($currencyOrder, 200);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }

    /**
     * apple商品列表.
     *
     * @param CommonConfig $config
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function productList(CommonConfig $config)
    {
        $products = $config->where('name', 'product')->where('namespace', 'apple')->first() ? json_decode($cash->value) : [];

        return response()->json($products);
    }
}
