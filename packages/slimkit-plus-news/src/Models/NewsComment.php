<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NewsComment extends Model
{
    protected $table = 'news_comments';

    /**
     * 单条评论属于一条资讯.
     * @return [type] [description]
     */
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'id');
    }

    public function scopeByNewsId(Builder $query, int $newsId)
    {
        return $query->where('news_id', $newsId);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
