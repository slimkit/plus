<?php

namespace Zhiyi\Plus\Traits\Model;

use Zhiyi\Plus\Models\Wallet;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserWallet
{
    /**
     * Bootstrap the trait.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function bootUserWallet()
    {
        // 用户创建后事件
        static::created(function ($user) {
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            if ($wallet === false) {
                return false;
            }
        });

        // 用户删除后事件
        static::deleted(function ($user) {
            $user->wallet()->delete();
        });
    }

    /**
     * 用户钱包.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'user_id', 'id');
    }
}
