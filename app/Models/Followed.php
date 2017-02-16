<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Followed extends Model
{
	protected $fillable = ['user_id', 'followed_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
