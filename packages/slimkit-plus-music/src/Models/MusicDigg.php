<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MusicDigg extends Model
{
    protected $table = 'music_diggs';

    protected $fillable = ['user_id', 'music_id'];
}
