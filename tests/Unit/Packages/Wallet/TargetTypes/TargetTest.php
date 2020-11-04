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

namespace Zhiyi\Plus\Tests\Unit\Packages\Wallet\TargetTypes;

use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TargetTypes\Target;
use Zhiyi\Plus\Tests\TestCase;

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
