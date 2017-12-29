<?php

namespace Zhiyi\Plus\Tests\Unit\Packages\Wallet\TargetTypes;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TargetTypes\Target;

class TargetTest extends TestCase
{
    /**
     * Test target setOrder method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSetOrder()
    {
        $target = $this->getMockForAbstractClass(TestTargetClass::class);
        $order = $this->createMock(Order::class);

        $target->setOrder($order);
        $this->assertSame($order, $target->getOrder());
    }
}

abstract class TestTargetClass extends Target
{
    public function getOrder()
    {
        return $this->order;
    }
}
