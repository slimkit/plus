<?php

namespace Zhiyi\Plus\Packages\Wallet\TargetTypes;

class WidthdrawTarget extends Target
{
    const ORDER_TITLE = '提现';
    protected $ownerWallet;

    public function handle(): bool
    {
        dd(1);
        // TODO
    }
}
