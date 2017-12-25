<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use DB;
use Zhiyi\Plus\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MusicComment extends Model
{
    protected $table = 'music_comments';

    /**
     * 单条评论属于一条音乐
     * @return [type] [description]
     */
    public function music()
    {
        return $this->belongsTo(Music::class, 'music_id', 'id');
    }

    /**
     * 单条评论属于一条专辑
     * @return [type] [description]
     */
    public function special()
    {
        return $this->belongsTo(MusicSpecial::class, 'special_id', 'id');
    }
}
