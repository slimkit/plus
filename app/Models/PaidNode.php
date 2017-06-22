<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class PaidNode extends Model
{
    /**
     * Get pays.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function pays()
    {
        return $this->hasMany(PaidNodeUser::class, 'node_id', 'id');
    }
}
