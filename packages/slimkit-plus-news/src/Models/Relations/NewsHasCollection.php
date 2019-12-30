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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\Relations;

use Illuminate\Support\Facades\Cache;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;
use Zhiyi\Plus\Models\User;

trait NewsHasCollection
{
    /**
     * 资讯收藏记录.
     *
     * @author bs<414606094@qq.com>
     * @return null|HasMany
     */
    public function collections()
    {
        return $this->hasMany(NewsCollection::class, 'news_id', 'id');
    }

    /**
     * 判断用户是否已收藏.
     *
     * @author bs<414606094@qq.com>
     * @param  User|int $user
     * @return bool
     */
    public function collected($user): bool
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('news-collection:%s,%s', $this->id, $user);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $status = $this->collections()->where('user_id', $user)->first() !== null;

        Cache::forever($cacheKey, $status);

        return $status;
    }

    /**
     * 收藏操作.
     *
     * @author bs<414606094@qq.com>
     * @param  User|int $user
     * @return mix
     */
    public function collection($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('news-collection:%s,%s', $this->id, $user);
        Cache::forget($cacheKey);

        return $this->getConnection()->transaction(function () use ($user) {
            return $this->collections()->firstOrCreate([
                'user_id' => $user,
            ]);
        });
    }

    /**
     * 取消收藏.
     *
     * @author bs<414606094@qq.com>
     * @param  User|int $user
     * @return mix
     */
    public function unCollection($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('news-collection:%s,%s', $this->id, $user);
        Cache::forget($cacheKey);

        $collection = $this->collections()->where('user_id', $user)->first();

        return $this->getConnection()->transaction(function () use ($collection) {
            $collection->delete();

            return true;
        });
    }
}
