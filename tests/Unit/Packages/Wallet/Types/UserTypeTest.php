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

namespace Zhiyi\Plus\Tests\Unit\Packages\Wallet\Types;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\Types\UserType;

class UserTypeTest extends TestCase
{
    /**
     * Test Transfer.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testTransfer()
    {
        $owner = 1;
        $target = 2;
        $amount = 100;

        // Create a order mock.
        $order = $this->getMockBuilder(Order::class)
                      ->setMethods(['autoComplete'])
                      ->getMock();

        // Set order::autoComplete return.
        $order->expects($this->once())
              ->method('autoComplete')
              ->willReturn(true);

        // Create a UserType mock.
        $userType = $this->getMockBuilder(UserType::class)
                         ->setMethods(['createOrder'])
                         ->getMock();

        // Set UserType::createOrder return.
        $userType->expects($this->once())
                 ->method('createOrder')
                 ->with($this->equalTo($owner), $this->equalTo($target), $this->equalTo($amount))
                 ->will($this->returnValue($order));

        $this->assertTrue($userType->transfer($owner, $target, $amount));
    }
}
