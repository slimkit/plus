<?php

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

namespace Zhiyi\Plus\Tests\Unit\Packages\Wallet;

use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TargetTypeManager;
use Zhiyi\Plus\Packages\Wallet\TargetTypes\UserTarget;
use Zhiyi\Plus\Tests\TestCase;

class TargetTypeManagerTest extends TestCase
{
    /**
     * Test TargetTypeManager.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testBaseClass()
    {
        // Create a WalletOrderModel::class
        $model = new WalletOrderModel();

        // Create a Order::class mock.
        $order = $this->getMockBuilder(Order::class)
                      ->onlyMethods(['getOrderModel'])
                      ->getMock();
        $order->expects(self::once())
              ->method('getOrderModel')
              ->willReturn($model);

        // Create a TargetTypeManager::class
        $targetTypeManager = new TargetTypeManager($this->app);
        $targetTypeManager->setOrder($order);

        // dd(Order::TARGET_TYPE_USER);

        // test getDefaultDriver.
        $model->target_type = Order::TARGET_TYPE_USER;
        self::assertSame(Order::TARGET_TYPE_USER, $targetTypeManager->getDefaultDriver());

        // test Order::TARGET_TYPE_USER Driver instance of.
        self::assertInstanceOf(UserTarget::class, $targetTypeManager->driver(Order::TARGET_TYPE_USER));
    }
}
