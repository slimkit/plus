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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Relations;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\CacheName\CacheKeys;
use Zhiyi\Plus\Models\Like;
use Zhiyi\Plus\Models\User;

trait FeedHasLike
{
    /**
     * Has likes.
     *
     * @return MorphMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Check user like.
     *
     * @param  mixed  $user
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function liked($user): bool
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $status = Cache::rememberForever(
            sprintf(CacheKeys::LIKED, $this->id, $user),
            function () use ($user) {
                return $this->likes()
                    ->where('user_id', $user)
                    ->exists();
            });

        return $status;
    }

    /**
     * Like feed.
     *
     * @param  mixed  $user
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function like($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }
        $this->forgetLike($user);

        return $this->getConnection()->transaction(function () use ($user) {
            $this->increment('like_count', 1);

            // 增加用户点赞数
            $this->user->extra()->firstOrCreate([])
                ->increment('likes_count', 1);

            return $this->likes()->firstOrCreate([
                'user_id'     => $user,
                'target_user' => $this->user_id,
            ]);
        });
    }

    /**
     * Unlike feed.
     *
     * @param  mixed  $user
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function unlike($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $like = $this->likes()->where('user_id', $user)->first();
        $this->forgetLike($user);

        return $like
            && $this->getConnection()->transaction(function () use ($like) {
                $this->decrement('like_count', 1);
                $this->user->extra()->decrement('likes_count', 1);
                $like->delete();

                return true;
            });
    }

    /**
     * Forget like cache.
     *
     * @param  mixed  $user
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function forgetLike($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        Cache::forget(sprintf(CacheKeys::LIKED, $this->id, $user));
    }
}
