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

use Illuminate\Contracts\Routing\ResponseFactory as ContractResponse;
use Illuminate\Support\Arr;
use Zhiyi\Plus\Http\Requests\API2\StoreWalletRecharge;

class WalletRechargeApplePayController extends WalletRechargeController
{
    /**
     * Create a Apple Pay recharge charge.
     *
     * @param  \Zhiyi\Plus\Http\Requests\API2\StoreWalletRecharge; $request
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function create(
        StoreWalletRecharge $request,
        ContractResponse $response
    ) {
        $model = $this->createChargeModel($request, 'applepay_upacp');
        $charge = $this->createCharge($model);

        $model->charge_id = $charge['id'];
        $model->transaction_no = Arr::get($charge, 'credential.applepay_upacp.tn');
        $model->saveOrFail();

        return $response
            ->json(['id' => $model->id, 'charge' => $charge])
            ->setStatusCode(201);
    }
}
