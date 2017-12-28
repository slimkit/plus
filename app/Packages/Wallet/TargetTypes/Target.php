<?php

namespace Zhiyi\Plus\Packages\Wallet\TargetTypes;

use Zhiyi\Plus\Packages\Wallet\Order;

abstract class Target
{
    protected $order;

    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    abstract public function handle(): bool;
}
