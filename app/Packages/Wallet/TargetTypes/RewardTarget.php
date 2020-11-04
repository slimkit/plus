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

namespace Zhiyi\Plus\Packages\Wallet\TargetTypes;

use DB;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\Wallet;

class RewardTarget extends Target
{
    const ORDER_TITLE = '打赏';
    protected $ownerWallet;     // \Zhiyi\Plus\Packages\Wallet\Wallet
    protected $targetWallet;    // \Zhiyi\Plus\Packages\Wallet\Wallet
    protected $targetRewardOrder; // Zhiyi\Plus\Packages\Wallet\Order

    /**
     * Handle.
     *
     * @return mixed
     * @author hh <915664508@qq.com>
     */
    public function handle($extra): bool
    {
        if (! $this->order->hasWait()) {
            return true;
        }

        $this->initWallet();

        $this->createTargetRewordOrder($extra['order']['target_order_body']);

        $transaction = function () use ($extra) {
            $this->order->saveStateSuccess();
            $this->targetRewardOrder->saveStateSuccess();
            $this->transfer($this->order, $this->ownerWallet);
            $this->transfer($this->targetRewardOrder, $this->targetWallet);

            // 记录打赏记录
            $this->createRewardRecord($extra['reward_resource'], $this->order);

            return true;
        };

        $transaction->bindTo($this);

        if (($result = DB::transaction($transaction)) === true) {
            // 发送消息通知
            $this->sendNotification($extra);
        }

        return $result;
    }

    /**
     * return target Order.
     *
     * @return mixed
     */
    public function getTargetOrder()
    {
        return $this->targetRewardOrder->getOrderModel();
    }

    /**
     * Send notification.
     *
     * @return void
     * @author hh <915664508@qq.com>
     */
    protected function sendNotification($extra)
    {
        //
    }

    /**
     * Init owner and target user wallet.
     *
     * @return void
     * @author hh <915664508@qq.com>
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
     * @author hh <915664508@qq.com>
     */
    protected function createTargetRewordOrder(string $body)
    {
        $order = new WalletOrderModel();
        $order->owner_id = $this->targetWallet->getWalletModel()->owner_id;
        $order->target_type = Order::TARGET_TYPE_REWARD;
        $order->target_id = $this->ownerWallet->getWalletModel()->owner_id;
        $order->title = static::ORDER_TITLE;
        $order->type = $this->getTargetRewordOrderType();
        $order->amount = $this->order->getOrderModel()->amount;
        $order->state = Order::STATE_WAIT;
        $order->body = $body;

        $this->targetRewardOrder = new Order($order);
    }

    /**
     * Get target user order type.
     *
     * @return int
     * @author hh <915664508@qq.com>
     */
    protected function getTargetRewordOrderType(): int
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
     * @author hh <915664508@qq.com>
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

    /**
     * 记录打赏.
     *
     * @param $resource
     * @param $order
     */
    protected function createRewardRecord($resource, $order)
    {
        $orderModel = $order->getOrderModel();

        $resource->reward($orderModel->owner_id, $orderModel->amount);
    }
}
