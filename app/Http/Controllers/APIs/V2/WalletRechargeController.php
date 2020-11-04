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

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Http\Request;
use Illuminate\Support\Str as StrAlias;
use Zhiyi\Plus\Http\Requests\API2\StoreWalletRecharge;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Services\Wallet\Charge as WalletChargeService;

class WalletRechargeController extends Controller
{
    /**
     * Container.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;
    /**
     * Wallet charge service.
     *
     * @var \Zhiyi\Plus\Services\Wallet\Charge
     */
    protected $chargeService;

    /**
     * Create controller instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(
        ApplicationContract $app,
        WalletChargeService $chargeService
    ) {
        $this->app = $app;
        $this->chargeService = $chargeService;
    }

    /**
     * Create a recharge charge.
     *
     * @param  \Zhiyi\Plus\Http\Requests\API2\StoreWalletRecharge  $request
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreWalletRecharge $request)
    {
        // 设置共享实例，一面多个地方重复调用验证
        $this->app->singleton(StoreWalletRecharge::class,
            function () use ($request) {
                return $request;
            });

        $type = $request->input('type');
        $controllers = [
            WalletRechargeApplePayController::class => ['applepay_upacp'],
            WalletRechargeAlipayController::class   => [
                'alipay', 'alipay_wap', 'alipay_pc_direct', 'alipay_qr',
            ],
            WalletRechargeWeChatController::class   => ['wx', 'wx_wap'],
        ];

        foreach ($controllers as $controller => $keys) {
            if (! in_array($type, $keys)) {
                continue;
            }

            return $this->app->call([$this->app->make($controller), 'create']);
        }

        $this->app->abort(406, '请求动作不存在或者非法');
    }

    /**
     * Resolve store method.
     *
     * @param  string  $type
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveStore(string $type)
    {
        return $this->app->call([$this, Str::camel($type.'_store')]);
    }

    /**
     * Create a recharge charge.
     *
     * @param  \Zhiyi\Plus\Models\WalletCharge  $charge
     * @param  array  $extra
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createCharge(
        WalletChargeModel $charge,
        array $extra = []
    ) {
        return $this->chargeService->create($charge, $extra);
    }

    /**
     * Create a charge model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $channel
     *
     * @return \Zhiyi\Plus\Models\WalletCharge
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createChargeModel(Request $request, string $channel): WalletChargeModel
    {
        $charge = new WalletChargeModel();
        $charge->user_id = $request->user()->id;
        $charge->channel = $channel;
        $charge->action = 1; // 充值都是为 增项
        $charge->amount = intval($request->input('amount'));
        $charge->subject = '余额充值';
        $charge->body = '账户余额充值';
        $charge->status = 0; // 待操作状态

        return $charge;
    }
}
