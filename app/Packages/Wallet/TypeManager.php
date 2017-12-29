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

namespace Zhiyi\Plus\Packages\Wallet;

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
        return Order::TARGET_TYPE_USER;
    }

    /**
     * Create user driver.
     *
     * @return \Zhiyi\Plus\Packages\Wallet\Types\Type
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createUserDriver(): Type
    {
        return $this->app->make(Types\UserType::class);
    }
}
