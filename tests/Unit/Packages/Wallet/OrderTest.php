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
use Zhiyi\Plus\Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * Test order.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testOrderSetAndGetAndConstruct()
    {
        $order = new Order(new WalletOrderModel());

        self::assertInstanceOf(WalletOrderModel::class, $order->getOrderModel());
    }

    /**
     * Test hasSuccess method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testHasSuccess()
    {
        $model = new WalletOrderModel();
        $model->state = Order::STATE_SUCCESS;

        // Create a Order::class mock.
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getOrderModel'])
            ->getMock();
        $order->expects(self::once())
            ->method('getOrderModel')
            ->willReturn($model);

        self::assertTrue($order->hasSuccess());
    }

    /**
     * Test hasFail method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testHasFail()
    {
        $model = new WalletOrderModel();
        $model->state = Order::STATE_FAIL;

        // Create a Order::class mock.
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getOrderModel'])
            ->getMock();
        $order->expects(self::once())
            ->method('getOrderModel')
            ->willReturn($model);

        $this->assertTrue($order->hasFail());
    }

    /**
     * Test hasWait method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testHasWait()
    {
        $model = new WalletOrderModel();
        $model->state = Order::STATE_WAIT;

        // Create a Order::class mock.
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getOrderModel'])
            ->getMock();
        $order->expects(self::once())
            ->method('getOrderModel')
            ->willReturn($model);

        self::assertTrue($order->hasWait());
    }

    /**
     * Test save method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSave()
    {
        // Create a WalletOrderModel::class mock.
        $model = $this->getMockBuilder(WalletOrderModel::class)
            ->onlyMethods(['save'])
            ->getMock();
        $model->expects(self::once())
            ->method('save')
            ->willReturn(true);

        // Create a Order::class mock.
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getOrderModel'])
            ->getMock();
        $order->expects(self::once())
            ->method('getOrderModel')
            ->willReturn($model);

        self::assertTrue($order->save());
    }

    /**
     * Test saveStateSucces method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSaveStateSuccess()
    {
        $model = new WalletOrderModel();

        // Create a Order::class mock.
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getOrderModel', 'save'])
            ->getMock();
        $order->expects(self::exactly(2))
            ->method('getOrderModel')
            ->willReturn($model);
        $order->expects(self::once())
            ->method('save')
            ->willReturn(true);

        self::assertTrue($order->saveStateSuccess());
        self::assertSame(Order::STATE_SUCCESS, $order->getOrderModel()->state);
    }

    /**
     * Test saveStateFail method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSaveStateFail()
    {
        $model = new WalletOrderModel();

        // Create a Order::class mock.
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getOrderModel', 'save'])
            ->getMock();
        $order->expects(self::exactly(2))
            ->method('getOrderModel')
            ->willReturn($model);
        $order->expects(self::once())
            ->method('save')
            ->willReturn(true);

        self::assertTrue($order->saveStateFial());
        self::assertSame(Order::STATE_FAIL, $order->getOrderModel()->state);
    }

    /**
     * Test autoComplete method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testAutoComplete()
    {
        // Create a model.
        $model = new WalletOrderModel();

        // Create a Order::class mock.
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getOrderModel', 'getTargetTypeManager'])
            ->getMock();
        $order->expects(self::exactly(3))
            ->method('getOrderModel')
            ->willReturn($model);

        // Test success
        $model->state = Order::STATE_SUCCESS;
        self::assertTrue($order->autoComplete());

        // Test fail.
        $model->state = Order::STATE_FAIL;
        self::assertTrue($order->autoComplete());

        // Create a TargetTypeManager::class mock.
        $targetManager = $this->getMockBuilder(TargetTypeManager::class)
            ->addMethods(['handle'])
            ->onlyMethods(['setOrder'])
            ->setConstructorArgs([$this->app])
            ->getMock();
        $targetManager->expects(self::once())
            ->method('setOrder')
            ->with(self::identicalTo($order))
            ->will(self::returnSelf());
        $targetManager->expects(self::once())
            ->method('handle')
            ->with(self::equalTo(true))
            ->willReturn(true);
        $order->expects(self::once())
            ->method('getTargetTypeManager')
            ->willReturn($targetManager);

        $model->state = Order::STATE_WAIT;
        self::assertTrue($order->autoComplete(true));
    }

    /**
     * Test getTargetTypeManager method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGetTargetTypeManager()
    {
        $order = new Order();

        self::assertInstanceOf(TargetTypeManager::class, $order->getTargetTypeManager());
    }
}
