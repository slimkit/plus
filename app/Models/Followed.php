<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Followed extends Model
{
	protected $fillable = ['user_id', 'followed_user_id'];

    public function user()
    {
    	return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function scopeByUserId(Builder $query, int $user_id): Builder
    {
    	return $query->where('user_id', $user_id);
    }
}
