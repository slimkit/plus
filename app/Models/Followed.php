<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Followed extends Model
{
    protected $fillable = ['user_id', 'followed_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    /**
     *  粉丝的关注列表  用于预加载判断对方关注状态
     *
     * @author bs<414606094@qq.com>
     *
     * @return [type] [description]
     */
    public function following()
    {
        return $this->hasMany(Following::class, 'user_id', 'user_id');
    }
}
