<?php

namespace SlimKit\PlusCheckIn\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

class CheckinLog extends Model
{
    public function onwer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
