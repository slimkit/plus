<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Menu extends Model
{
    /**
     * The model QueryBudeler scope func.
     *
     * @param Builder $query Query builder
     * @param string  $type  type value
     *
     * @return Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', 'like', $type);
    }
}
