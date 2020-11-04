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

namespace Zhiyi\Plus\Models\Relations;

use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Models\BlackList;
use Zhiyi\Plus\Models\User;

trait UserHasBlackList
{
    /**
     * get blacklists of current user.
     * @Author   Wayne
     * @DateTime 2018-04-08
     * @Email    qiaobin@zhiyicx.com
     * @return   [type]              [description]
     */
    public function blacklists()
    {
        return $this->hasMany(BlackList::class, 'user_id', 'id');
    }

    /**
     * is user blacked by current_user.
     * @Author   Wayne
     * @DateTime 2018-04-18
     * @Email    qiaobin@zhiyicx.com
     * @param    [type]              $user [description]
     * @return   [type]                    [description]
     */
    public function blacked($user): bool
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('user-blacked:%s,%s', $user, $this->id);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $status = $this->blacklists()
            ->where('target_id', $user)
            ->first() !== null;
        Cache::forever($cacheKey, $status);

        return $status;
    }
}
