<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MusicCollection extends Model
{
    protected $table = 'music_collections';

    protected $fillable = ['user_id', 'special_id'];
}
