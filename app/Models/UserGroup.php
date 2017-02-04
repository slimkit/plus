<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    public function nodes()
    {
        return $this->hasMany(NodeLink::class, 'user_group_id', 'id');
    }
}
