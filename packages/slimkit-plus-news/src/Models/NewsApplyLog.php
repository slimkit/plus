<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

class NewsApplyLog extends Model
{
    protected $table = 'news_apply_logs';

    protected $fillable = ['user_id', 'news_id', 'status'];

    public function news()
    {
        return $this->hasOne(News::class, 'id', 'news_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
