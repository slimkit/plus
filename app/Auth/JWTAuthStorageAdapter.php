<?php

namespace Zhiyi\Plus\Auth;

use Closure;
use Carbon\Carbon;
use Zhiyi\Plus\Models\JWTCache;
use Tymon\JWTAuth\Providers\Storage\StorageInterface;

class JWTAuthStorageAdapter implements StorageInterface
{
    /**
     * Add a new item into storage.
     *
     * @param string $key
     * @param string $value
     * @param int $minutes
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function add($key, $value, $minutes)
    {
        $this->payloadSingle('add', function ($key, $value, $minutes) {
            $token = JWTCache::find($key);
            if (! $token) {
                $token = new JWTCache();
                $token->user_id = 0;
                $token->key = $key;
                $token->value = $value;
            }

            $token->minutes = $minutes;
            $token->status = 1;
            $token->save();
        }, $key, $value, $minutes);
    }

    /**
     * Check whether a key exists in storage.
     *
     * @param string $key
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function has($key)
    {
        return $this->payloadSingle('has', function ($key) {
            $token = JWTCache::find($key);

            if (! $token) {
                return false;
            }

            $now = Carbon::now();
            if ($token->status && $now->diffInMinutes($token->created_at) < $token->minutes) {
                return true;
            } elseif ($now->diffInMinutes($token->created_at) > $token->minutes) {
                $token->delete();
            }

            return false;
        }, $key);
    }

    /**
     * Remove an item from storage.
     *
     * @param string $key
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy($key)
    {
        return $this->payloadSingle('destroy', function ($key) {
            return JWTCache::destroy($key);
        }, $key);
    }

    /**
     * Remove all items associated.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function flush()
    {
        $this->payloadSingle('flush', function () {
            JWTCache::delete();
        });
    }

    /**
     * Pay load single token.
     *
     * @param string $method
     * @param \Closure $call
     * @param array $args
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function payloadSingle(string $method, Closure $call, ...$args)
    {
        if (! config('jwt.single_auth')) {
            $storage = app(
                config('jwt.providers.cache_storage')
            );

            return $storage->$method(...$args);
        }

        return $call(...$args);
    }
}
