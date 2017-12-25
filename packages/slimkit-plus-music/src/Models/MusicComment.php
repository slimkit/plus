<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Illuminate\Database\Eloquent\Model;

class MusicComment extends Model
{
    protected $table = 'music_comments';

    /**
     * 单条评论属于一条音乐.
     * @return [type] [description]
     */
    public function music()
    {
        return $this->belongsTo(Music::class, 'music_id', 'id');
    }

    /**
     * 单条评论属于一条专辑.
     * @return [type] [description]
     */
    public function special()
    {
        return $this->belongsTo(MusicSpecial::class, 'special_id', 'id');
    }
}
