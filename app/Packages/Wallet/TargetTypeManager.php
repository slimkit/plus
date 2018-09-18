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

use Illuminate\Support\Manager;
use Zhiyi\Plus\Packages\Wallet\TargetTypes\Target;

class TargetTypeManager extends Manager
{
    protected $order;

    /**
     * Set the manager order.
     *
     * @param \Zhiyi\Plus\Packages\Wallet\Order $order
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the order target type driver.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getDefaultDriver()
    {
        return $this->order->getOrderModel()->target_type;
    }

    /**
     * Create user target type driver.
     *
     * @return \Zhiyi\Plus\Packages\TargetTypes\Target
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createUserDriver(): Target
    {
        $driver = $this->app->make(TargetTypes\UserTarget::class);
        $driver->setOrder($this->order);

        return $driver;
    }

    /**
     * Create widthdraw target type driver.
     *
     * @return \Zhiyi\Plus\Packages\TargetTypes\Target
     * @author BS <414606094@qq.com>
     */
    protected function createWidthdrawDriver(): Target
    {
        $driver = $this->app->make(TargetTypes\WidthdrawTarget::class);
        $driver->setOrder($this->order);

        return $driver;
    }

    /**
     * Create Rew target type driver.
     *
     * @return \Zhiyi\Plus\Packages\TargetTypes\Target
     * @author hh <915664508@qq.com>
     */
    protected function createRewardDriver(): Target
    {
        $driver = $this->app->make(TargetTypes\RewardTarget::class);
        $driver->setOrder($this->order);

        return $driver;
    }

    /**
     * Create Charge target type driver.
     *
     * @return \Zhiyi\Plus\Packages\TargetTypes\Target
     * @author BS <414606094@qq.com>
     */
    protected function createRechargePingPPDriver(): Target
    {
        $driver = $this->app->make(TargetTypes\RechargeTarget::class);
        $driver->setOrder($this->order);

        return $driver;
    }

    /**
     * Create Transform target type driver.
     *
     * @return \Zhiyi\Plus\Packages\TargetTypes\Target
     * @author BS <414606094@qq.com>
     */
    protected function createTransformDriver(): Target
    {
        $driver = $this->app->make(TargetTypes\TransformCurrencyTarget::class);
        $driver->setOrder($this->order);

        return $driver;
    }
}
