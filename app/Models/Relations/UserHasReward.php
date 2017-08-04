<?php

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Reward;

trait UserHasReward
{
    /**
     * 用户的被打赏记录.
     *
     * @author bs<414606094@qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function beRewardeds()
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
            return $this->beRewardeds()->create([
                'user_id' => $user,
                'target_user' => $this->id,
                'amount' => $amount,
            ]);
        });
    }
}
