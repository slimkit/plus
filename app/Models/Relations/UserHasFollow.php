<?php

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
}
