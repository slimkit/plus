<?php

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\Comment;

trait UserHasComment
{
    /**
     * Has comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
}
