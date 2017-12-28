<?php

namespace Zhiyi\Plus\Packages\Wallet;

use Illuminate\Support\Manager;
use Zhiyi\Plus\Packages\Wallet\Types\Type;

class TypeManager extends Manager
{
    /**
     * Get default type driver.
     *
     * @return string User type
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getDefaultDriver()
    {
        return Order::TARGET_TYPE_USER;
    }

    /**
     * Create user driver.
     *
     * @return \Zhiyi\Plus\Packages\Wallet\Types\Type
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createUserDriver(): Type
    {
        return $this->app->make(Types\TransferType::class);
    }
}
