<?php

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserHasFollow
{
    /**
     * æ­£åœ¨å
     * ³æ³¨ - æˆ‘å
     * ³æ³¨çš„.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followings(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_follow', 'user_id', 'target')
            ->withPivot('id')
            ->withTimestamps();
    }

    /**
     * å
     * ³æ³¨è€
     * - å
     * ³æ³¨æˆ‘çš„.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followers(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_follow', 'target', 'user_id')
            ->withPivot('id')
            ->withTimestamps();
    }

    /**
     * éªŒè¯æ˜¯å¦å
     * ³æ³¨.
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
     * éªŒè¯æ˜¯å¦å
     * ³æ³¨äº†æˆ‘.
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
}
