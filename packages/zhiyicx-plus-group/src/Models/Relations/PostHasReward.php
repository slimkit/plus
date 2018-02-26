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

use Zhiyi\Plus\Models\Reward;
use Zhiyi\Plus\Models\User;

trait PostHasReward
{
    /**
     * Rewards of post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphToMany
     * @author BS <414606094@qq.com>
     */
    public function rewards()
    {
        return $this->morphMany(Reward::class, 'rewardable');
    }

    /**
     * Reward a author of group post.
     *
     * @param  mix $user
     * @param  float $amount
     * @return mix
     */
    public function reward($user, $amount)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }
        return $this->getConnection()->transaction(function () use ($user, $amount) {
            return $this->rewards()->create([
                'user_id' => $user,
                'target_user' => $this->user_id,
                'amount' => $amount,
            ]);
        });
    }
}
