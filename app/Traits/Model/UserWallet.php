<?php

namespace Zhiyi\Plus\Traits\Model;

use Zhiyi\Plus\Models\Wallet;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserWallet
{
    /**
     * 用户钱包.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function wallet(): HasOne
    {
        $this->hasOne(Wallet::class, 'user_id', 'id');
    }
}
