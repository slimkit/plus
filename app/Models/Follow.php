<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'user_follow';

    protected $fillable = ['user_id', 'target'];

    public function followed()
    {
        return $this->hasOne($this, 'target', 'user_id');
    }
}
