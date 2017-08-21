<?php

namespace Zhiyi\Plus\Auth;

use Tymon\JWTAuth\JWTAuth;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\JWTCache as JWTCacheModel;

class JWTAuthToken
{
    /**
     * JWT auth manager.
     *
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create the auth instance.
     *
     * @param \Tymon\JWTAuth\JWTAuth $auth
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Create user token.
     *
     * @param UserModel $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function create(UserModel $user)
    {
        $token = $this->auth->fromUser($user);

        if (! $token) {
            return false;
        }

        return $this->token($token, $user);
    }

    /**
     * Refresh token.
     *
     * @param string $token
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function refresh(string $token)
    {
        $token = $this->auth->refresh($token);

        if (! $token) {
            return false;
        }

        return $this->token($token, $this->auth->toUser($token));
    }

    /**
     * Token save to database.
     *
     * @param string $token
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function token(string $token, UserModel $user): string
    {
        if (! config('jwt.single_auth')) {
            return $token;
        }

        JWTCacheModel::where('user_id', $user->id)->where('status', 0)->update([
            'status' => 1,
        ]);

        $payload = $this->auth->getPayload($token);
        $cache = new JWTCacheModel();
        $cache->user_id = $user->id;
        $cache->key = $payload['jti'];
        $cache->value = $token;
        $cache->minutes = max(config('jwt.ttl'), config('jwt.refresh_ttl'));
        $cache->save();

        return $token;
    }
}
