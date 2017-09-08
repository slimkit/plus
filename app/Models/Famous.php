<?php

namespace Zhiyi\Plus\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * 注册时关注用户模型
 */
class Famous extends Model
{
	protected $table = 'famous';

	protected $hidden = [
		'created_at',
		'updated_at',
		'id'
	];

	public function user ()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}