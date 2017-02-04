<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    public function scopeByUserId(Builder $query, string $user_id): Builder
    {
        return $query->where('user_id', $user_id);
    }
}
