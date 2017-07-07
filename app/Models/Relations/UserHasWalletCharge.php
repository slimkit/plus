<?php

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\WalletCharge;

trait UserHasWalletCharge
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
