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
use Zhiyi\Plus\Packages\Wallet\TargetTypes\UserTarget;

class UserType extends Type
{
    /**
     * User to user transfer.
     *
     * @param int|\Zhiyi\Plus\Models\User $owner
     * @param int|\Zhiyi\Plus\Models\User $target
     * @param int $amount
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function transfer($owner, $target, int $amount): bool
    {
        $owner = $this->resolveGetUserId($owner);
        $target = $this->resolveGetUserId($target);
        $order = $this->createOrder($owner, $target, $amount);

        return $order->autoComplete();
    }

    /**
     * Resolve get user id.
     *
     * @param int|\Zhiyi\Plus\Models\User $user
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveGetUserId($user): int
    {
        if ($user instanceof UserModel) {
            return $user->id;
        }

        return (int) $user;
    }

    /**
     * Create a order.
     *
     * @param int $owner
     * @param int $target
     * @param int $amount
     * @return \Zhiyi\Plus\Packages\Wallet\Order
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createOrder(int $owner, int $target, int $amount): Order
    {
        return new Order($this->createOrderModel($owner, $target, $amount));
    }

    /**
     * Create order model.
     *
     * @param int $owner
     * @param int $target
     * @param int $amount
     * @return \Ziyi\Plus\Models\WalletOrder
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createOrderModel(int $owner, int $target, int $amount): WalletOrderModel
    {
        $order = new WalletOrderModel();
        $order->owner_id = $owner;
        $order->target_type = Order::TARGET_TYPE_USER;
        $order->target_id = $target;
        $order->title = UserTarget::ORDER_TITLE;
        $order->type = Order::TYPE_EXPENSES;
        $order->amount = $amount;
        $order->state = Order::STATE_WAIT;

        return $order;
    }
}
