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

use RuntimeException;
use Illuminate\Support\Manager;
use Zhiyi\Plus\Packages\Wallet\Types\Type;

class TypeManager extends Manager
{
    /**
     * Get default type driver.
     *
     * @return string User type
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getDefaultDriver()
    {
        throw new RuntimeException('The manager not support default driver.');
    }

    /**
     * Create user driver.
     *
     * @return \Zhiyi\Plus\Packages\Wallet\Types\Type
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createUserDriver(): Type
    {
        return $this->app->make(Types\UserType::class);
    }

    /**
     * Create widthdraw driver.
     *
     * @return \Zhiyi\Plus\Packages\Wallet\Types\Type
     * @author BS <414606094@qq.com>
     */
    protected function createWidthdrawDriver(): Type
    {
        return $this->app->make(Types\WidthdrawType::class);
    }

    /**
     * Create reward driver.
     *
     * @return \Zhiyi\Plus\Packages\Wallet\Types\Type
     * @author hh <915664508@qq.com>
     */
    protected function createRewardDriver(): Type
    {
        return $this->app->make(Types\RewardType::class);
    }

    /**
     * Create recharge driver.
     *
     * @return \Zhiyi\Plus\Packages\Wallet\Types\Type
     * @author BS <414606094@qq.com>
     */
    protected function createRechargePingPPDriver(): Type
    {
        return $this->app->make(Types\RechargeType::class);
    }

    /**
     * Create transform driver.
     *
     * @return \Zhiyi\Plus\Packages\Wallet\Types\Type
     * @author BS <414606094@qq.com>
     */
    protected function createTransformDriver(): Type
    {
        return $this->app->make(Types\TransformCurrencyType::class);
    }
}
