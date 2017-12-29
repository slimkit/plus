<?php

namespace Zhiyi\Plus\Tests\Unit\Packages\Wallet;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Models\WalletOrder as WallerOrderModel;

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
        $order = new Order(new WallerOrderModel());

        $this->assertInstanceOf(WallerOrderModel::class, $order->getOrderModel());
    }
}
