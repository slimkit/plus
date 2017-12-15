<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Illuminate\Database\Eloquent\Model;

class NewsDigg extends Model
{
    protected $table = 'news_diggs';

    protected $fillable = ['user_id', 'news_id'];
}
