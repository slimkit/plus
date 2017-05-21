<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    protected $fillable = ['user_id', 'following_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'following_user_id', 'id');
    }

    public function scopeByUserId(Builder $query, int $user_id): Builder
    {
        return $query->where('user_id', $user_id);
    }

    /**
     *  当前用户被关注的列表  用于预加载判断对方关注状态
     *
     * @author bs<414606094@qq.com>
     *
     * @return [type] [description]
     */
    public function userFollowed()
    {
        return $this->hasMany(Followed::class, 'user_id', 'user_id');
    }

    public function followed()
    {
        return $this->hasOne(Followed::class, 'followed_user_id', 'user_id');
    }

    public function syncFollowed()
    {
        $followed = new Followed(['user_id' => $this->following_user_id, 'followed_user_id' => $this->user_id]);

        return $followed->save();
    }
}
