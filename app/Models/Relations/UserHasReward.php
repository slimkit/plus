<?php

namespace Zhiyi\Plus\Models\Relations;

use DB;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Reward;
use Illuminate\Support\Facades\Cache;

trait UserHasReward
{
    /**
     * 用户的被打赏记录.
     *
     * @author bs<414606094@qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function rewards()
    {
        return $this->morphMany(Reward::class, 'rewardable');
    }

    /**
     * 打赏用户.
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

        return $this->getConnection()->transaction(function () use ($user, $amount) {
            return $this->rewards()->create([
                'user_id' => $user,
                'target_user' => $this->id,
                'amount' => $amount,
            ]);
        });
    }
}
