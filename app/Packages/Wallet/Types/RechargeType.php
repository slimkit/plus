<?php

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

namespace Zhiyi\Plus\Packages\Wallet\Types;

use DB;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Packages\Wallet\TargetTypes\RechargeTarget;
use Zhiyi\Plus\Services\Wallet\Charge as WalletChargeService;

class RechargeType extends Type
{
    protected $allowType = [
        'applepay_upacp',
        'alipay',
        'alipay_wap',
        'alipay_pc_direct',
        'alipay_qr',
        'wx',
        'wx_wap',
    ];

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

        if ($this->checkRechargeArgs($type, $extra)) {
            $transaction = function () use ($order, $extra, $type) {
                $order->save();
                $pingppcharge = app(WalletChargeService::class)->newCreate($order->getOrderModel(), $type, $extra);

                return [
                    'pingpp_order' => $pingppcharge,
                    'order' => $order->getOrderModel(),
                ];
            };

            return DB::transaction($transaction);
        }

        return false;
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
     * 检测支付方式及额外参数.
     *
     * @param string $type
     * @param array $extra
     * @return boolen
     * @author BS <414606094@qq.com>
     */
    protected function checkRechargeArgs(string $type, array $extra): bool
    {
        if (in_array($type, $this->allowType)) {
            return $this->{camel_case('check_'.$type.'_extra')}($extra);
        }

        return false;
    }

    protected function checkApplepayUpacpExtra(array $extra): bool
    {
        return true;
    }

    protected function checkAlipayExtra(array $extra): bool
    {
        return true;
    }

    protected function checkAlipayWapExtra(array $extra): bool
    {
        return in_array('success_url', $extra);
    }

    protected function checkAlipayPcDirectExtra(array $extra): bool
    {
        return in_array('success_url', $extra);
    }

    protected function checkAlipayQrExtra(array $extra): bool
    {
        return in_array('success_url', $extra);
    }

    protected function checkWxExtra(array $extra): bool
    {
        return true;
    }

    protected function checkWxWapExtra(array $extra): bool
    {
        return in_array('success_url', $extra);
    }
}
