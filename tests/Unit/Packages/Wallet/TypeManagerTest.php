<?php

namespace Zhiyi\Plus\Tests\Unit\Packages\Wallet;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TypeManager;

class TypeManagerTest extends TestCase
{
    /**
     * Test get default driver return.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGetDefaultDriver()
    {
        $typeManager = $this->app->make(TypeManager::class);
        $defaultDriverString = $typeManager->getDefaultDriver();
        $this->assertSame(Order::TARGET_TYPE_USER, $defaultDriverString);
    }
}
