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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\Relations;

use DB;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Reward;
use Illuminate\Support\Facades\Cache;

trait NewsHasReward
{
    public function rewards()
    {
        return $this->morphMany(Reward::class, 'rewardable');
    }

    /**
     * Reward a author of news.
     *
     * @author bs<414606094@qq.com>
     * @param  mix $user
     * @param  float $amount
     * @return mix
     */
    public function reward($user, $amount)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('news-reward-count:%s', $this->id);
        Cache::forget($cacheKey);

        return $this->getConnection()->transaction(function () use ($user, $amount) {
            return $this->rewards()->create([
                'user_id' => $user,
                'target_user' => $this->user_id,
                'amount' => $amount,
            ]);
        });
    }

    /**
     * 打赏总数统计
     *
     * @author bs<414606094@qq.com>
     * @return mix
     */
    public function rewardCount()
    {
        $cacheKey = sprintf('news-reward-count:%s', $this->id);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $counts = $this->rewards()->select(DB::raw('count(*) as count, sum(amount) as amount'))->first()->toArray();

        Cache::forever($cacheKey, $counts);

        return $counts;
    }
}
