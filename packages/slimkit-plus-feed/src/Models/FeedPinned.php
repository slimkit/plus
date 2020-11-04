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

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\User;

class FeedPinned extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('user', function (Builder $query) {
            $query->with('user');
        });
    }

    /**
     * Has user.
     *
     * @return HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     *  Has feed.
     *
     * @return HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function feed()
    {
        return $this->hasOne(Feed::class, 'id', 'target');
    }

    public function commentFeed()
    {
        return $this->hasOne(Feed::class, 'id', 'raw');
    }

    /**
     * Has feed comment.
     *
     * @return HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function comment()
    {
        return $this->hasOne(CommentModel::class, 'id', 'target');
    }

    public function averages($type = 'feed', $date = '')
    {
        return self::newQuery()
            ->where('channel', 'like', $type)
            ->whereNotNull('expires_at')
            ->where('created_at', '>', $date)
            ->where('amount', '>', 0)
            ->where('day', '>', 0)
            ->first([
                DB::raw('SUM(day) as total_day'),
                DB::raw('SUM(amount) as total_amount'),
            ])
            ->toArray();
    }
}
