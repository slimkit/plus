<?php

namespace SlimKit\PlusCheckIn\Policies;

use Zhiyi\Plus\Models\User;

class CheckInPolicy
{
    /**
     * 检查用户是否可以创建签到记录.
     *
     * @param \Zhiyi\Plus\Models\User $user
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function create(User $user): bool
    {
        $date = $user->freshTimestamp()->format('Y-m-d');

        return ! $user->checkinLogs()
            ->whereDate('created_at', $date)
            ->first();
    }
}
