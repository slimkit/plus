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

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserHasFollow
{
    /**
     * follows - my following.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followings(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_follow', 'user_id', 'target')
            ->withPivot('id')
            ->orderBy('user_follow.id', 'desc')
            ->withTimestamps();
    }

    /**
     * followers - my followers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followers(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_follow', 'target', 'user_id')
            ->withPivot('id')
            ->orderBy('user_follow.id', 'desc')
            ->withTimestamps();
    }

    /**
     * Verification is concerned followed.
     *
     * @param int|\Zhiyi\Plus\Models\User $user
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function hasFollwing($user): bool
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        if (! $user) {
            return false;
        }

        return $this
            ->followings()
            ->newPivotStatementForId($user)
            ->value('target') === $user;
    }

    /**
     * Verify that I am followed.
     *
     * @param  int|\Zhiyi\Plus\Models\User $user
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function hasFollower($user): bool
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        if (! $user) {
            return false;
        }

        return $this
            ->followers()
            ->newPivotStatementForId($user)
            ->value('user_id') === $user;
    }

    /**
     * 相互关注的好友.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author ZsyD<1251992018@qq.com>
     */
    public function mutual(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_follow', 'user_id', 'target')
            ->join('user_follow as b', function ($join) {
                $join->on('user_follow.user_id', '=', 'b.target')
                ->on('user_follow.target', '=', 'b.user_id');
            })
            ->withPivot('id')
            ->orderBy('user_follow.id', 'desc')
            ->withTimestamps();
    }
}
