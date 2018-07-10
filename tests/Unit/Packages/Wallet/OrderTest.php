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

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TargetTypeManager;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;

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

        $this->assertInstanceOf(WalletOrderModel::class, $order->getOrderModel());
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
                      ->setMethods(['getOrderModel'])
                      ->getMock();
        $order->expects($this->exactly(1))
              ->method('getOrderModel')
              ->will($this->returnValue($model));

        $this->assertTrue($order->hasSuccess());
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
                      ->setMethods(['getOrderModel'])
                      ->getMock();
        $order->expects($this->exactly(1))
              ->method('getOrderModel')
              ->will($this->returnValue($model));

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
                      ->setMethods(['getOrderModel'])
                      ->getMock();
        $order->expects($this->exactly(1))
              ->method('getOrderModel')
              ->will($this->returnValue($model));

        $this->assertTrue($order->hasWait());
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
                      ->setMethods(['save'])
                      ->getMock();
        $model->expects($this->exactly(1))
              ->method('save')
              ->willReturn(true);

        // Create a Order::class mock.
        $order = $this->getMockBuilder(Order::class)
                      ->setMethods(['getOrderModel'])
                      ->getMock();
        $order->expects($this->exactly(1))
              ->method('getOrderModel')
              ->will($this->returnValue($model));

        $this->assertTrue($order->save());
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
                      ->setMethods(['getOrderModel', 'save'])
                      ->getMock();
        $order->expects($this->exactly(2))
              ->method('getOrderModel')
              ->willReturn($model);
        $order->expects($this->exactly(1))
              ->method('save')
              ->willReturn(true);

        $this->assertTrue($order->saveStateSuccess());
        $this->assertSame(Order::STATE_SUCCESS, $order->getOrderModel()->state);
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
                      ->setMethods(['getOrderModel', 'save'])
                      ->getMock();
        $order->expects($this->exactly(2))
              ->method('getOrderModel')
              ->willReturn($model);
        $order->expects($this->exactly(1))
              ->method('save')
              ->willReturn(true);

        $this->assertTrue($order->saveStateFial());
        $this->assertSame(Order::STATE_FAIL, $order->getOrderModel()->state);
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
                      ->setMethods(['getOrderModel', 'getTargetTypeManager'])
                      ->getMock();
        $order->expects($this->exactly(3))
              ->method('getOrderModel')
              ->willReturn($model);

        // Test success
        $model->state = Order::STATE_SUCCESS;
        $this->assertTrue($order->autoComplete());

        // Test fail.
        $model->state = Order::STATE_FAIL;
        $this->assertTrue($order->autoComplete());

        // Create a TargetTypeManager::class mock.
        $targetManager = $this->getMockBuilder(TargetTypeManager::class)
                              ->setMethods(['handle', 'setOrder'])
                              ->setConstructorArgs([$this->app])
                              ->getMock();
        $targetManager->expects($this->exactly(1))
                      ->method('setOrder')
                      ->with($this->identicalTo($order))
                      ->will($this->returnSelf());
        $targetManager->expects($this->exactly(1))
                      ->method('handle')
                      ->with($this->equalTo(true))
                      ->willReturn(true);
        $order->expects($this->exactly(1))
              ->method('getTargetTypeManager')
              ->willReturn($targetManager);

        $model->state = Order::STATE_WAIT;
        $this->assertTrue($order->autoComplete(true));
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

        $this->assertInstanceOf(TargetTypeManager::class, $order->getTargetTypeManager());
    }
}
