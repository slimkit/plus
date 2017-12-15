<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCateFollow extends Model
{
    protected $table = 'news_cates_follow';

    protected $fillable = ['user_id', 'follows'];

    public $timestamps = false;
}
