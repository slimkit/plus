<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Requests\API2\StoreWalletRecharge;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Services\Wallet\Charge as WalletChargeService;
use Illuminate\Contracts\Routing\ResponseFactory as ContractResponse;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

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
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ApplicationContract $app, WalletChargeService $chargeService)
    {
        $this->app = $app;
        $this->chargeService = $chargeService;
    }

    /**
     * Create a recharge charge.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\StoreWalletRecharge $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreWalletRecharge $request)
    {
        $method = camel_case($request->input('type').'_store');

        return $this->app->call([$this, $method]);
    }

    /**
     * Create a APP rechrage by Alipay.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function alipayStore(Request $request, ContractResponse $response)
    {
        $model = $this->createChargeModel($request, 'alipay');
        $charge = $this->createCharge($model);

        $model->charge_id = $charge['id'];
        $model->saveOrFail();

        return $response
            ->json(['id' => $model->id, 'charge' => $charge])
            ->setStatusCode(201);
    }

    /**
     *  Create Apple Pay recharge charge.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function applepayUpacpStore(Request $request, ContractResponse $response)
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

    /**
     * Create a recharge charge.
     *
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @param array $extra
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createCharge(WalletChargeModel $charge, array $extra = [])
    {
        return $this->chargeService->create($charge, $extra);
    }

    /**
     * Create a charge model.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $channel
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
