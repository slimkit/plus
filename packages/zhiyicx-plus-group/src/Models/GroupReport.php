<?php

namespace Zhiyi\PlusGroup\Models;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class GroupReport extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_reports';

    /**
     * Has user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Has target user.
     *
     * @return mixed
     */
    public function target()
    {
        return $this->hasOne(User::class, 'id', 'target_id');
    }

    /**
     * Has resource.
     *
     * @return mixed
     */
    public function resource()
    {
        if ($this->type === 'post') {
            return $this->hasOne(Post::class, 'id', 'resource_id');
        } else {
            return $this->hasOne(Comment::class, 'id', 'resource_id');
        }
    }

    /**
     * group
     */
    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }
}
