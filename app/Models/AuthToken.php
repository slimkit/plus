<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    // 关联users表
    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    /**
     * 查找条件依照token的复用方法.
     *
     * @param Builder $query 查询对象
     * @param string  $token Token值
     *
     * @return Builder 查询对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByToken(Builder $query, $token)
    {
        return $query->where('token', $token);
    }
}
