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
use Zhiyi\Plus\Repository\WalletPingPlusPlus;
use Zhiyi\Plus\Services\Wallet\Charge as ChargeService;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;

class PingPlusPlusChargeWebHooks
{
    public function webhook(Request $request, WalletPingPlusPlus $repository, ChargeService $chargeService)
    {
        if ($request->json('type') !== 'charge.succeeded') {
            return response('不是支持的事件', 422);
        }

        $signature = $request->headers->get('x-pingplusplus-signature');
        $pingPlusPlusPublicCertificate = $repository->get()['public_key'] ?? null;

        $signed = openssl_verify($request->getContent(), base64_decode($signature), $pingPlusPlusPublicCertificate, OPENSSL_ALGO_SHA256);

        if (! $signed) {
            return response('加密验证失败', 422);
        }

        $pingPlusPlusCharge = $request->json('data.object');
        $charge = WalletChargeModel::find($chargeService->unformatChargeId($pingPlusPlusCharge['order_no']));

        if (! $charge) {
            return response('凭据不存在', 404);
        } elseif ($charge->status === 1) {
            return response('订单已提前完成');
        }

        $user = $charge->user;
        $charge->status = 1;
        $charge->transaction_no = $pingPlusPlusCharge['transaction_no'];
        $charge->account = $this->resolveChargeAccount($pingPlusPlusCharge, $charge->account);

        $user->getConnection()->transaction(function () use ($user, $charge) {
            $user->wallet()->increment('balance', $charge->amount);
            $charge->save();
        });

        return response('通知成功');
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
