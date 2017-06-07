<?php

namespace Zhiyi\Plus\Models\Concerns;

use Zhiyi\Plus\Models\WalletCharge;

trait UserWalletCharge
{
    /**
     * User wallet charges.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function walletCharges()
    {
        return $this->hasMany(WalletCharge::class, 'user_id', 'id');
    }
}
