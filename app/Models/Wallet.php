<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wallet extends Model
{
    /**
     * 获取钱包所属用户.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user(): HasOne
    {
        $this->hasOne(User::class, 'id', 'user_id');
    }
}
