<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletRecord extends Model
{
    use SoftDeletes;

    /**
     * User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     *  Account by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'account', 'id');
    }
}
