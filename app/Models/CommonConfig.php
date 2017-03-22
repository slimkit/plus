<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CommonConfig extends Model
{
    /**
     * Scope func to namespace.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string                               $namespace
     *
     * @return Illuminate\Database\Eloquent\Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByNamespace(Builder $query, string $namespace): Builder
    {
        return $query->where('namespace', $namespace);
    }

    /**
     * Scope func to name.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string                               $name
     *
     * @return Illuminate\Database\Eloquent\Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }
}
