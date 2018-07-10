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

use Zhiyi\Plus\Http\Requests\API2\StoreWalletRecharge;
use Illuminate\Contracts\Routing\ResponseFactory as ContractResponse;

class WalletRechargeApplePayController extends WalletRechargeController
{
    /**
     * Create a Apple Pay recharge charge.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\StoreWalletRecharge; $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function create(StoreWalletRecharge $request, ContractResponse $response)
    {
        $model = $this->createChargeModel($request, 'applepay_upacp');
        $charge = $this->createCharge($model);

        $model->charge_id = $charge['id'];
        $model->transaction_no = array_get($charge, 'credential.applepay_upacp.tn');
        $model->saveOrFail();

        return $response
            ->json(['id' => $model->id, 'charge' => $charge])
            ->setStatusCode(201);
    }
}
