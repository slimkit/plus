<?php

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

namespace Zhiyi\Plus\Tests\Unit\Packages\Wallet;

use RuntimeException;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TypeManager;
use Zhiyi\Plus\Packages\Wallet\Types\UserType;

class TypeManagerTest extends TestCase
{
    protected $typeManager;

    /**
     * Setup the test environment.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setUp()
    {
        parent::setUp();

        $this->typeManager = $this->app->make(TypeManager::class);
    }

    /**
     * Test get default driver return.
     * @expectedException RuntimeException
     * @expectedExceptionMessage The manager not support default driver.
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGetDefaultDriver()
    {
        $this->typeManager->getDefaultDriver();
    }

    /**
     * Test Create user driver.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testCreateUserDriver()
    {
        $userType = $this->typeManager->driver(Order::TARGET_TYPE_USER);

        $this->assertInstanceOf(UserType::class, $userType);
    }
}
