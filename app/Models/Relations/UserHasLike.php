<?php

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\Like;

trait UserHasLike
{
    /**
     * Has likes for user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
