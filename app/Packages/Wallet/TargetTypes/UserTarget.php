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

namespace Zhiyi\Plus\Packages\Wallet\TargetTypes;

use DB;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\Wallet;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;

class UserTarget extends Target
{
    const ORDER_TITLE = '转账';
    protected $ownerWallet;     // \Zhiyi\Plus\Packages\Wallet\Wallet
    protected $targetWallet;    // \Zhiyi\Plus\Packages\Wallet\Wallet
    protected $targetUserOrder; // Zhiyi\Plus\Packages\Wallet\Order

    /**
     * Handle.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle(): bool
    {
        if (! $this->order->hasWait()) {
            return true;
        }

        $this->initWallet();
        $this->createTargetUserOrder();

        $transaction = function () {
            $this->order->saveStateSuccess();
            $this->targetUserOrder->saveStateSuccess();
            $this->transfer($this->order, $this->ownerWallet);
            $this->transfer($this->targetUserOrder, $this->targetWallet);

            return true;
        };
        $transaction->bindTo($this);

        if (($result = DB::transaction($transaction)) === true) {
            $this->sendNotification();
        }

        return $result;
    }

    /**
     * Send notification.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function sendNotification()
    {
        // todo.
    }

    /**
     * Init owner and target user wallet.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function initWallet()
    {
        // Target user wallet.
        $this->targetWallet = new Wallet(
            $this->order->getOrderModel()->target_id
        );

        // owner wallet.
        $this->ownerWallet = new Wallet(
            $this->order->getOrderModel()->owner_id
        );
    }

    /**
     * Create target user order.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createTargetUserOrder()
    {
        $order = new WalletOrderModel();
        $order->owner_id = $this->targetWallet->getWalletModel()->owner_id;
        $order->target_type = Order::TARGET_TYPE_USER;
        $order->target_id = $this->ownerWallet->getWalletModel()->owner_id;
        $order->title = static::ORDER_TITLE;
        $order->type = $this->getTargetUserOrderType();
        $order->amount = $this->order->getOrderModel()->amount;
        $order->state = Order::STATE_WAIT;

        $this->targetUserOrder = new Order($order);
    }

    /**
     * Get target user order type.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getTargetUserOrderType(): int
    {
        if ($this->order->getOrderModel()->type === Order::TYPE_INCOME) {
            return Order::TYPE_EXPENSES;
        }

        return Order::TYPE_INCOME;
    }

    /**
     * Transfer.
     *
     * @param \Zhiyi\Plus\Packages\Wallet\Order $order
     * @param \Zhiyi\Plus\Packages\Wallet\Wallet $wallet
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function transfer(Order $order, Wallet $wallet)
    {
        $methods = [
            Order::TYPE_INCOME => 'increment',
            Order::TYPE_EXPENSES => 'decrement',
        ];
        $method = $methods[$order->getOrderModel()->type];
        $wallet->$method($order->getOrderModel()->amount);
    }
}
