<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AuthToken extends Model
{
    // 关联users表
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Find the condition according to the token reuse method.
     *
     * @param Builder $query 查询对象
     * @param string  $token Token值
     *
     * @return Builder 查询对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByToken(Builder $query, $token): Builder
    {
        return $query->where('token', $token);
    }

    public function scopeByRefreshToken(Builder $query, $refresh_token): Builder
    {
        return $query->where('refresh_token', $refresh_token);
    }

    /**
     * 查询排序条件复用倒叙.
     *
     * @param Builder $query 查询对象
     *
     * @return Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeOrderByDesc(Builder $query): Builder
    {
        return $query->orderBy('id', 'desc');
    }
}
