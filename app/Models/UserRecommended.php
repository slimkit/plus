<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecommended extends Model
{
    protected $table = 'users_recommended';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
