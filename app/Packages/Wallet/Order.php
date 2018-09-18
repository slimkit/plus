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

namespace Zhiyi\Plus\Packages\Wallet;

use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;

class Order
{
    /**
     * target types.
     */
    const TARGET_TYPE_USER = 'user';                           // 用户之间转账
    const TARGET_TYPE_RECHARGE_PING_P_P = 'recharge_ping_p_p'; // Ping ++ 充值
    const TARGET_TYPE_REWARD = 'reward';                       // 打赏
    const TARGET_TYPE_WITHDRAW = 'widthdraw';                  // 提现
    const TARGET_TYPE_TRANSFORM = 'transform';                 // 兑换货币、积分

    /**
     * types.
     */
    const TYPE_INCOME = 1;    // 收入
    const TYPE_EXPENSES = -1; // 支出

    /**
     * state types.
     */
    const STATE_WAIT = 0;    // 等待
    const STATE_SUCCESS = 1; // 成功
    const STATE_FAIL = -1;   // 失败

    /**
     * The order model.
     *
     * @var \Zhiyi\Plus\Models\WalletOrder
     */
    protected $order;

    /**
     * Init order or create a order.
     *
     * @param mixed $order
     * @param array $args see static::createOrder method
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct($order = null)
    {
        if ($order instanceof WalletOrderModel) {
            $this->setOrderModel($order);
        }
    }

    /**
     * Set order model.
     *
     * @param \Zhiyi\Plus\Models\WalletOrder $order
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setOrderModel(WalletOrderModel $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order model.
     *
     * @return \Zhiyi\Plus\Models\WalletOrder
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getOrderModel(): WalletOrderModel
    {
        return $this->order;
    }

    /**
     * Has order success.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function hasSuccess(): bool
    {
        return $this->getOrderModel()->state === static::STATE_SUCCESS;
    }

    /**
     * Has order fail.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function hasFail(): bool
    {
        return $this->getOrderModel()->state === static::STATE_FAIL;
    }

    /**
     * Has order wait.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function hasWait(): bool
    {
        return $this->getOrderModel()->state === static::STATE_WAIT;
    }

    /**
     * Save order save method.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function save()
    {
        return $this->getOrderModel()->save();
    }

    /**
     * Save and set order [state=1].
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function saveStateSuccess()
    {
        $this->getOrderModel()->state = static::STATE_SUCCESS;

        return $this->save();
    }

    /**
     * Save and set order [state=-1].
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function saveStateFial()
    {
        $this->getOrderModel()->state = static::STATE_FAIL;

        return $this->save();
    }

    /**
     * Auth complete.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function autoComplete(...$arguments): bool
    {
        if (! $this->hasWait()) {
            return true;
        }

        $manager = $this->getTargetTypeManager();
        $manager->setOrder($this);

        return $manager->handle(...$arguments);
    }

    /**
     * Get TargetTypeManager instance.
     *
     * @return \Zhiyi\Plus\Packages\Wallet\TargetTypeManager
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetTypeManager(): TargetTypeManager
    {
        return app(TargetTypeManager::class);
    }
}
