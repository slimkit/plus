<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
