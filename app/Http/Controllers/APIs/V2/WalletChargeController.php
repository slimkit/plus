<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Services\Wallet\Charge as WalletChargeService;
use Illuminate\Contracts\Routing\ResponseFactory as ContractResponse;

class WalletChargeController extends Controller
{
    /**
     * Get a charge.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ContractResponse $response, WalletCharge $charge)
    {
        $mode = $request->query('mode') === 'retrieve';
        // prem.
        if ($request->user()->id !== $charge->user_id) {
            return $response
                ->json(['message' => ['当前用户无权限查询该订单']])
                ->setStatusCode(403);

        // retrueve.
        } elseif ($mode === true && $charge->status === 0) {
            $this->retrieveCharge($charge, $request->user());
        }

        return $response
            ->json($charge)
            ->setStatusCode(200);
    }

    /**
     * Retrieve charge.
     *
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @return \Zhiyi\Plus\Models\WalletCharge
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function retrieveCharge(WalletCharge &$charge, User $user): WalletCharge
    {
        if (! $charge->charge_id) {
            abort(422, '取回订单非支付类型凭据');
        }

        //  Ping++ charge.
        $pingppCharge = app(WalletChargeService::class)->query(
            $charge->charge_id
        );

        $charge->transaction_no = $pingppCharge['transaction_no'];
        $charge->status = $pingppCharge['paid'] === true ? 1 : $charge->status;
        $charge->account = $this->resolveChargeAccount($pingppCharge, $charge->account);

        $user->getConnection()->transaction(function () use ($charge, $user) {
            if ($charge->action !== 1) {
                return;
            }

            $user->wallet()->increment('balance', $charge->amount);
            $charge->save();
        });

        return $charge;
    }

    /**
     * 解决付款订单来源.
     *
     * @param array $charge
     * @param string|null $default
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveChargeAccount($charge, $default = null)
    {
        $channel = array_get($charge, 'channel');
        // 支付宝渠道
        if (in_array($channel, ['alipay', 'alipay_wap', 'alipay_pc_direct', 'alipay_qr'])) {
            return array_get($charge, 'extra.buyer_account', $default); // 支付宝付款账号
        // 微信渠道
        } elseif (in_array($channel, ['wx', 'wx_pub', 'wx_pub_qr', 'wx_wap', 'wx_lite'])) {
            return array_get($charge, 'extra.open_id', $default); // 用户唯一 open_id
        }

        return $default;
    }
}
