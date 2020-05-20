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

namespace SlimKit\PlusCheckIn\Policies;

use Illuminate\Support\Facades\Cache;
use SlimKit\PlusCheckIn\CacheName\CheckInCacheName;
use Zhiyi\Plus\Models\User;

class CheckInPolicy
{
    /**
     * 检查用户是否可以创建签到记录.
     *
     * @param  User  $user
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function create(User $user): bool
    {
        $date = $user->freshTimestamp()->format('Y-m-d');

        return ! Cache::rememberForever(sprintf(CheckInCacheName::CheckInAtDate,
            $user->id, $date), function () use ($date, $user) {
                return $user->checkinLogs()
                ->whereDate('created_at', $date)
                ->first();
            });
    }
}
