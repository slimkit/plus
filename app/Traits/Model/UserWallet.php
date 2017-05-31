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
        static::created(function ($user) {
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            if ($wallet === false) {
                return false;
            }
        });
    }

    /**
     * 监听用户删除事件.
     *
     * @param self $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function deletedUserWallet($user)
    {
        $user->wallet()->delete();
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
