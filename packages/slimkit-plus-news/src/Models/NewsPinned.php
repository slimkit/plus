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

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\User;

class NewsPinned extends Model
{
    protected $table = 'news_pinneds';

    public function news()
    {
        if ($this->channel === 'news:comment') {
            return $this->hasOne(News::class, 'id', 'target');
        }

        return $this->hasOne(News::class, 'id', 'target');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function comment()
    {
        return $this->hasOne(CommentModel::class, 'id', 'raw');
    }

    public function averages($type = 'news', $date = '')
    {
        return self::newQuery()
            ->where('channel', '=', $type)
            ->whereNotNull('expires_at')
            ->where('created_at', '>', $date)
            ->where('amount', '>', 0)
            ->where('day', '>', 0)
            ->first([
                \DB::raw('SUM(day) as total_day'),
                \DB::raw('SUM(amount) as total_amount'),
            ])
            ->toArray();
    }
}
