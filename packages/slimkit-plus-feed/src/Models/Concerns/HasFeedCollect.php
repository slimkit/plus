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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Concerns;

use Illuminate\Support\Facades\Cache;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\CacheName\CacheKeys;
use Zhiyi\Plus\Models\User;

trait HasFeedCollect
{
    /**
     * 动态收藏用户列表.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function collections()
    {
        return $this->belongsToMany(User::class, 'feed_collections', 'feed_id',
            'user_id')
            ->withTimestamps();
    }

    /**
     * check if user has collected.
     *
     * @param  int  $user
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function collected(int $user): bool
    {
        $status = Cache::rememberForever(sprintf(CacheKeys::COLLECTED,
            $this->id, $user), function () use ($user) {
                return $this->collections()
                ->newPivotStatementForId($user)
                ->exists();
            });

        return $status;
    }

    /**
     * 动态收藏.
     *
     * @param  int  $user
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function collect(int $user)
    {
        $this->forgetCollet($this->id, $user);

        return $this->collections()->attach($user);
    }

    /**
     * 取消动态收藏.
     *
     * @param  int  $user
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function uncollect(int $user)
    {
        $this->forgetCollet($this->id, $user);

        return $this->collections()->detach($user);
    }

    /**
     * Clean up cache.
     *
     * @param  int  $feed
     * @param  int  $user
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function forgetCollet(int $feed, int $user)
    {
        Cache::forget(sprintf(CacheKeys::COLLECTED,
            $feed, $user));
    }
}
