<?php

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\FileWith;

trait UserHasFilesWith
{
    /**
     * user files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function files()
    {
        return $this->hasMany(FileWith::class, 'user_id', 'id');
    }
}
