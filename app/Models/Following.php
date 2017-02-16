<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
	protected $fillable = ['user_id', 'following_user_id'];

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeByUserId(Builder $query, int $user_id): Builder
    {
    	return $query->where('user_id', $user_id);
    }
}
