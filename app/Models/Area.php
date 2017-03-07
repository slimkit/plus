<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    /**
     * Scope func by pid.
     *
     * @param Builder $query
     * @param int     $pid
     *
     * @return Illuminate\Database\Eloquent\Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByPid(Builder $query, int $pid): Builder
    {
        return $query->where('pid', $pid);
    }

    public function parent()
    {
        return $this->hasOne(__CLASS__, 'id', 'pid');
    }
}
