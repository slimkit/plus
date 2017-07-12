<?php

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\Comment;

trait UserHasComment
{
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
}
