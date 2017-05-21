<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserDatas extends Model
{
    protected $table = 'user_datas';

    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    /**
     * 通过用户id查看统计数据.
     *
     * @author bs<414606094@qq.com>
     *
     * @param Builder $query   [description]
     * @param int     $user_id [description]
     *
     * @return [type] [description]
     */
    public function scopeByUserId(Builder $query, int $user_id): Builder
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * 通过key查看统计数据.
     *
     * @author bs<414606094@qq.com>
     *
     * @param Builder $query   [description]
     * @param int     $user_id [description]
     *
     * @return [type] [description]
     */
    public function scopeByKey(Builder $query, $key): Builder
    {
        return $query->where('key', $key);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
