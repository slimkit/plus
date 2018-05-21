<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class NativePayOrder extends Model
{
    protected $table = 'native_pay_orders';

    public function walletCharge()
    {
        return $this->hasOne(WalletCharge::class, 'charge_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
