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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Requests\API2\StoreCurrencyAppleIAPRecharge;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;
use Zhiyi\Plus\Packages\Currency\Processes\AppStorePay as AppStorePayProcess;

class CurrencyApplePayController extends Controller
{
    /**
     * 发起充值订单.
     *
     * @param StoreCurrencyAppleIAPRecharge $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(StoreCurrencyAppleIAPRecharge $request)
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
     * @param CurrencyOrderModel $currencyOrder
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function retrieve(Request $request, CurrencyOrderModel $order)
    {
        $receipt = $request->input('receipt');

        $retrieve = new AppStorePayProcess();
        if ($retrieve->verifyReceipt($receipt, $order) === true) {
            return response()->json($order, 200);
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
        $products = ($datas = $config->where('name', 'product')->where('namespace', 'apple')->first()) ? array_values(json_decode($datas->value, true)) : [];

        return response()->json($products, 200);
    }
}
