<?php

namespace Zhiyi\Plus\Packages\Wallet;

use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\WalletOrder as WallerOrderModel;

class Order
{
    /**
     * target types.
     */
    const TARGET_TYPE_USER = 'user';                           // 用户之间转账
    const TARGET_TYPE_RECHARGE_PING_P_P = 'recharge_ping_p_p'; // Ping ++ 充值
    const TARGET_TYPE_REWARD = 'reward';                       // 打赏
    const TARGET_TYPE_WITHDRAW = 'widthdraw'                   // 提现

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
    public function __construct($order = null,)
    {
        if ($order instanceof WallerOrderModel) {
            $this->setOrderModel($order);
        }
    }

    /**
     * Set order model
     *
     * @param \Zhiyi\Plus\Models\WalletOrder $order
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setOrderModel(WallerOrderModel $order)
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
    public function getOrderModel(): WallerOrderModel
    {
        return $this->order;
    }

    public function hasSuccess(): bool
    {
        return $this->getOrderModel()->state === static::STATE_SUCCESS;
    }

    public function hasFail(): bool
    {
        return $this->getOrderModel()->state === static::STATE_FAIL;
    }

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
        return $this->order->save();
    }

    /**
     * Save and set order [state=1].
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function saveStateSuccess()
    {
        $this->order->state = static::STATE_SUCCESS;

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
        $this->order->state = static::STATE_FAIL;

        return $this->save();
    }

    /**
     * Auth complete.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function autoComplete(): bool
    {
        if ($this->order->state !== static::STATE_WAIT) {
            return true;
        }

        $manager = app(TargetTypeManager::class)
        $manager->setOrder($this);

        return $manager->handle();
    }
}
