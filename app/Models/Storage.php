<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storage extends Model
{
    use SoftDeletes;

    /**
     * 复用的hash可拓展条件方法.
     *
     * @param Builder $query    构建对象
     * @param string  $hash     文件hash值
     * @param string  $operator 值等条件
     * @param mixed   $boolean  比对条件
     *
     * @return Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByHash(Builder $query, string $hash, $operator = '=', $boolean = 'and'): Builder
    {
        return $query->where('hash', $operator, $hash, $boolean);
    }
}
