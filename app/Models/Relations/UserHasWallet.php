<?php

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\Wallet;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserHasWallet
{
    /**
     * Bootstrap the trait.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function bootUserHasWallet()
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
     * User wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'user_id', 'id');
    }
}
