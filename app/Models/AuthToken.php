<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
	// 关联users表
    public function user()
    {
    	return $this->hasOne(User::class, 'user_id', 'id');
    }
}
