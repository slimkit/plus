<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * 父地区.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function parent()
    {
        return $this->hasOne(__CLASS__, 'id', 'pid');
    }

    /**
     * 子地区.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function items()
    {
        return $this->hasMany(__CLASS__, 'pid');
    }
}
