<?php

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

namespace Zhiyi\Plus\Observers;

use Zhiyi\Plus\Models\Famous;
use Zhiyi\Plus\Models\User;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  User  $user
     *
     * @return void
     */
    public function created(User $user)
    {
        // 处理默认关注和默认相互关注
        $famous = Famous::query()->with('user')->get()
            ->groupBy('type');
        $famous
            ->map(function ($type, $key) use ($user) {
                $users = $type->filter(function ($famou) {
                    return $famou->user !== null;
                })->pluck('user');
                $user->followings()->attach($users->pluck('id'));
                // 相互关注
                if ($key === 'each') {
                    $users->map(function ($source) use ($user) {
                        $source->followings()->attach($user);
                    });
                }
            });
    }
}
