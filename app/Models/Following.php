<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
	protected $fillable = ['user_id', 'following_user_id'];
	
    public function user()
    {
    	return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
