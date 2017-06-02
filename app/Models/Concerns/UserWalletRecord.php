<?php

namespace Zhiyi\Plus\Models\Concerns;

use Zhiyi\Plus\Models\WalletRecord;

trait UserWalletRecord
{
    /**
     * 用户记录明细.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function walletRecords()
    {
        return $this->hasMany(WalletRecord::class, 'user_id', 'id');
    }
}
