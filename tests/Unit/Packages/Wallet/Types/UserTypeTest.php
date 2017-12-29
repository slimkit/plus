<?php

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
