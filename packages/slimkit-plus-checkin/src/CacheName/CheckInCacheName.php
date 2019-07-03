<?php

declare(strict_types=1);

namespace SlimKit\PlusCheckIn\CacheName;

class CheckInCacheName
{
    // 当日锁
    const CheckInAtDate = 'user_checkedIn:%d_at_%s';
    // 签到锁
    const CheckLocked = 'checkin_lock_for_%d';
}
