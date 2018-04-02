<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models;

use Illuminate\Database\Eloquent\Model;

class FeedVideo extends Model
{
    public function fileWith()
    {
        return $this->hasOne(FileWith::class, 'id', 'video_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
