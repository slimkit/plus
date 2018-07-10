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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

class FeedCollection extends Model
{
    protected $table = 'feed_collections';
    protected $fillable = [
        'user_id',
        'feed_id',
    ];

    public function feed()
    {
        return $this->belongsTo(Feed::class, 'id', 'feed_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
