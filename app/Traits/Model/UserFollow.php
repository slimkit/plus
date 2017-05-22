<?php

namespace Zhiyi\Plus\Traits\Model;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserFollw
{
    /**
     * 正在关注 - 我关注的.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followings(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_follow', 'user_id', 'target')
            ->withTimestamps();
    }

    /**
     * 关注着 - 关注我的.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followers(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_follow', 'target', 'user_id')
            ->withTimestamps();
    }

    /**
     * 验证是否关注.
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

        return $this
            ->followings()
            ->where($this->getKeyName(), $user)
            ->value('target') === $user;
    }
}
