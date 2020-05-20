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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models;

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\User;

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
