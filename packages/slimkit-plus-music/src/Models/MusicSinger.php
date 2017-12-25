<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\FileWith;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusicSinger extends Model
{
	use SoftDeletes;
    protected $table = 'music_singers';

    public function cover()
    {
    	return $this->hasOne(FileWith::class, 'id', 'cover')->select('id','size');
    }
}
