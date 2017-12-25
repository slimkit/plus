<?php

namespace SlimKit\PlusQuestion\Models\Relations;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Collection;
use Illuminate\Support\Facades\Cache;

trait AnswerHasCollect
{
    /**
     * Has collectors for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function collectors()
    {
        return $this->morphMany(Collection::class, 'collectible');
    }

    /**
     * Check user like.
     *
     * @param mixed $user
     * @return bool
     * @author bs<414606094@qq.com>
     */
    public function collected($user): bool
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('question-answer-collect:%s,%s', $this->id, $user);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $status = $this->collectors()
            ->where('user_id', $user)
            ->first() !== null;

        Cache::forever($cacheKey, $status);

        return $status;
    }

    /**
     * Collect an answer.
     *
     * @param mixed $user
     * @return mixed
     * @author bs<414606094@qq.com>
     */
    public function collect($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $this->forgetCollect($user);

        return $this->getConnection()->transaction(function () use ($user) {
            return $this->collectors()->firstOrCreate([
                'user_id' => $user,
            ]);
        });
    }

    /**
     * Cancel collect an answer.
     *
     * @param mixed $user
     * @return mixed
     * @author bs<414606094@qq.com>
     */
    public function unCollect($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $this->forgetCollect($user);

        return $this->getConnection()->transaction(function () use ($user) {
            return $this->collectors()->where('user_id', $user)->delete();
        });
    }

    /**
     * Forget collect cache.
     *
     * @param mixed $user
     * @return void
     * @author bs<414606094@qq.com>
     */
    public function forgetCollect($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $cacheKey = sprintf('question-answer-collect:%s,%s', $this->id, $user);
        Cache::forget($cacheKey);
    }
}
