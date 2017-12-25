<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Relations;

use Zhiyi\Plus\Models\Like;
use Zhiyi\Plus\Models\User;
use Illuminate\Support\Facades\Cache;

trait MusicHasLike
{
    /**
     * Has likes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Check user like.
     *
     * @param mixed $user
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function liked($user): bool
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('music-like:%s,%s', $this->id, $user);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $status = $this->likes()
            ->where('user_id', $user)
            ->first() !== null;

        Cache::forever($cacheKey, $status);

        return $status;
    }

    /**
     * Like feed.
     *
     * @param mixed $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function like($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }
        $this->forgetLike($user);

        return $this->getConnection()->transaction(function () use ($user) {

            return $this->likes()->firstOrCreate([
                'user_id' => $user,
                'target_user' => 0,
            ]);
        });
    }

    /**
     * Unlike feed.
     *
     * @param mixed $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function unlike($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $like = $this->likes()->where('user_id', $user)->first();
        $this->forgetLike($user);

        return $like && $this->getConnection()->transaction(function () use ($like) {
            $like->delete();

            return true;
        });
    }

    /**
     * Forget like cache.
     *
     * @param mixed $user
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function forgetLike($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('music-like:%s,%s', $this->id, $user);
        Cache::forget($cacheKey);
    }
}
