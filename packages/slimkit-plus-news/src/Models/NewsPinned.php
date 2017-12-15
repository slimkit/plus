<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\Comment as CommentModel;

class NewsPinned extends Model
{
    protected $table = 'news_pinneds';

    public function news()
    {
        if ($this->channel === 'news:comment') {
            return $this->hasOne(News::class, 'id', 'target');
        }

        return $this->hasOne(News::class, 'id', 'target');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function comment()
    {
        return $this->hasOne(CommentModel::class, 'id', 'raw');
    }
}
