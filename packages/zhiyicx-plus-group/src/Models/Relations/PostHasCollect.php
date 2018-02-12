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

namespace Zhiyi\PlusGroup\Models\Relations;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Collection;
use Illuminate\Support\Facades\Cache;

trait PostHasCollect
{
    /**
     * Has collectors for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function collectors()
    {
        return $this->morphMany(Collection::class, 'collectible');
    }

    /**
     * Check user like.
     *
     * @param mixed $user
     * @return bool
     * @author bs<414606094@qq.com>
     */
    public function collected($user): bool
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('group-post-collect:%s,%s', $this->id, $user);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $status = $this->collectors()
                ->where('user_id', $user)
                ->first() !== null;

        Cache::forever($cacheKey, $status);

        return $status;
    }

    /**
     * Collect an answer.
     *
     * @param mixed $user
     * @return mixed
     * @author bs<414606094@qq.com>
     */
    public function collect($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $this->forgetCollect($user);

        return $this->getConnection()->transaction(function () use ($user) {
            return $this->collectors()->firstOrCreate([
                'user_id' => $user,
            ]);
        });
    }

    /**
     * Cancel collect an answer.
     *
     * @param mixed $user
     * @return mixed
     * @author bs<414606094@qq.com>
     */
    public function unCollect($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $this->forgetCollect($user);

        return $this->getConnection()->transaction(function () use ($user) {
            return $this->collectors()->where('user_id', $user)->delete();
        });
    }

    /**
     * Forget collect cache.
     *
     * @param mixed $user
     * @return void
     * @author bs<414606094@qq.com>
     */
    public function forgetCollect($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('group-post-collect:%s,%s', $this->id, $user);
        Cache::forget($cacheKey);
    }
}
