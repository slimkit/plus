<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $table = 'sms';

    /**
     * 局部复用手机号码条件方法.
     *
     * @param Builder $query 构造器
     * @param string  $phone 手机号码
     *
     * @return Builder 构造器
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByPhone(Builder $query, string $phone): Builder
    {
        return $query->where('to', $phone);
    }

    public function scopeByDesc(Builder $query): Builder
    {
        return $query->orderBy('id', 'desc');
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = serialize($value);
    }

    public function getDataAttribute($value)
    {
        return unserialize($value);
    }
}
