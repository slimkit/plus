<?php

namespace Slimkit\PlusAroundAmap\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AroundAmap extends Model
{
	protected $primaryKey = 'user_id';

	public $incrementing = false;

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}