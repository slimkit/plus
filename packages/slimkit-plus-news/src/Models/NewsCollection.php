<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCollection extends Model
{
    protected $table = 'news_collections';

    protected $fillable = ['user_id', 'news_id'];
}
