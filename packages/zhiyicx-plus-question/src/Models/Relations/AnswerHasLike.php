<?php

namespace SlimKit\PlusQuestion\Models\Relations;

use Zhiyi\Plus\Models\Like;
use Zhiyi\Plus\Models\User;
use Illuminate\Support\Facades\Cache;

trait AnswerHasLike
{
    /**
     * Has be likes for answer.
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
     * @author bs<414606094@qq.com>
     */
    public function liked($user): bool
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('question-answer-like:%s,%s', $this->id, $user);
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
     * Like an answer.
     *
     * @param mixed $user
     * @return mixed
     * @author bs<414606094@qq.com>
     */
    public function like($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $this->forgetLike($user);

        return $this->getConnection()->transaction(function () use ($user) {
            $this->increment('likes_count', 1);

            return $this->likes()->firstOrCreate([
                'user_id' => $user,
                'target_user' => $this->user_id,
            ]);
        });
    }

    /**
     * Unlike an answer.
     *
     * @param mixed $user
     * @return mixed
     * @author bs<414606094@qq.com>
     */
    public function unlike($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $this->forgetLike($user);

        return $this->getConnection()->transaction(function () use ($user) {
            $this->decrement('likes_count', 1);

            return $this->likes()->where(['user_id' => $user, 'target_user' => $this->user_id])->delete();
        });
    }

    /**
     * Forget like cache.
     *
     * @param mixed $user
     * @return void
     * @author bs<414606094@qq.com>
     */
    public function forgetLike($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('question-answer-like:%s,%s', $this->id, $user);
        Cache::forget($cacheKey);
    }
}
