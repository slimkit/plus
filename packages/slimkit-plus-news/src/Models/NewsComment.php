<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\User;

class NewsComment extends Model
{
    protected $table = 'news_comments';

    /**
     * 单条评论属于一条资讯.
     * @return [type] [description]
     */
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'id');
    }

    public function scopeByNewsId(Builder $query, int $newsId)
    {
        return $query->where('news_id', $newsId);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
