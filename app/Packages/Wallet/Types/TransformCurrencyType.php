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

use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Packages\Wallet\TargetTypes\TransformCurrencyTarget;

class TransformCurrencyType extends Type
{
    /**
     * 钱包兑换积分.
     *
     * @param $owner
     * @param int $amount
     * @return bool
     * @author BS <414606094@qq.com>
     */
    public function transform($owner, int $amount): bool
    {
        $owner = $this->checkUserId($owner);
        $order = $this->createOrder($owner, $amount);

        return $order->autoComplete();
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
        $order->target_type = Order::TARGET_TYPE_TRANSFORM;
        $order->target_id = 0;
        $order->title = TransformCurrencyTarget::ORDER_TITLE;
        $order->body = '兑换积分';
        $order->type = Order::TYPE_EXPENSES;
        $order->amount = $amount;
        $order->state = Order::STATE_WAIT;

        return new Order($order);
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
}
