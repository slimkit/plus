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

namespace Zhiyi\Plus\Packages\Wallet\Types;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Repository\WalletPingPlusPlus;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Packages\Wallet\TargetTypes\RechargeTarget;
use Zhiyi\Plus\Services\Wallet\Charge as WalletChargeService;

class RechargeType extends Type
{
    /**
     * 创建充值订单.
     *
     * @param $owner
     * @param int $amount
     * @param string $type
     * @param array $extra
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function create($owner, int $amount, string $type, array $extra = [])
    {
        $owner = $this->checkUserId($owner);
        $order = $this->createOrder($owner, $amount);

        if (app(WalletChargeService::class)->checkRechargeArgs($type, $extra)) {
            $transaction = function () use ($order, $extra, $type) {
                $pingppCharge = app(WalletChargeService::class)->newCreate($order->getOrderModel(), $type, $extra);
                $order->getOrderModel()->target_id = $pingppCharge->id;
                $order->save();

                return [
                    'pingpp_order' => $pingppCharge,
                    'order' => $order->getOrderModel(),
                ];
            };

            return DB::transaction($transaction);
        }

        return false;
    }

    /**
     * 主动取回凭据.
     *
     * @return boolen
     * @author BS <414606094@qq.com>
     */
    public function retrieve(WalletOrderModel $walletOrder): bool
    {
        $pingppCharge = app(WalletChargeService::class)->query($walletOrder->target_id);

        if ($pingppCharge['paid'] === true) {
            return $this->complete($walletOrder);
        }

        return false;
    }

    /**
     * 异步回调通知.
     *
     * @param Request $request
     * @return boolen
     * @author BS <414606094@qq.com>
     */
    public function webhook(Request $request): bool
    {
        if ($this->verifyWebHook($request)) {
            $pingppCharge = $request->json('data.object');
            $walletOrder = WalletChargeModel::find(app(WalletChargeService::class)->unformatChargeId($pingppCharge['order_no']));

            if ($walletOrder || $walletOrder->status === 1) {
                return false;
            }

            return $this->complete($walletOrder);
        }

        return false;
    }

    /**
     * 完成订单.
     *
     * @return boolen
     * @author BS <414606094@qq.com>
     */
    public function complete(WalletOrderModel $walletOrder): bool
    {
        $order = new Order($walletOrder);

        return $order->autoComplete();
    }

    /**
     * Check user.
     *
     * @param int|UserModel $user
     * @return int
     * @author BS <414606094@qq.com>
     */
    protected function checkUserId($user): int
    {
        if ($user instanceof UserModel) {
            $user = $user->id;
        }

        return (int) $user;
    }

    /**
     * Create Order.
     *
     * @param int $owner
     * @param int $amount
     * @return Zhiyi\Plus\Models\WalletOrderModel
     * @author BS <414606094@qq.com>
     */
    protected function createOrder(int $owner, int $amount): Order
    {
        $order = new WalletOrderModel();
        $order->owner_id = $owner;
        $order->target_type = Order::TARGET_TYPE_RECHARGE_PING_P_P;
        $order->target_id = 0;
        $order->title = RechargeTarget::ORDER_TITLE;
        $order->body = '余额充值';
        $order->type = Order::TYPE_INCOME;
        $order->amount = $amount;
        $order->state = Order::STATE_WAIT;

        return new Order($order);
    }

    /**
     * 解决付款订单来源.
     *
     * @param array $charge
     * @param string|null $default
     * @return string|null
     * @author BS <414606094@qq.com>
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

    /**
     * 验证回调信息.
     *
     * @param Request $request
     * @return boolen
     * @author BS <414606094@qq.com>
     */
    protected function verifyWebHook(Request $request): bool
    {
        if ($request->json('type') !== 'charge.succeeded') {
            return false;
        }

        $signature = $request->headers->get('x-pingplusplus-signature');
        $pingPlusPlusPublicCertificate = app(WalletPingPlusPlus::class)->get()['public_key'] ?? null;
        $signed = openssl_verify($request->getContent(), base64_decode($signature), $pingPlusPlusPublicCertificate, OPENSSL_ALGO_SHA256);

        if (! $signed) {
            return false;
        }

        return true;
    }
}
