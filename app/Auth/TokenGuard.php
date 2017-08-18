<?php

namespace Zhiyi\Plus\Auth;

use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;

class TokenGuard implements Guard
{
    use GuardHelpers;

    /**
     * The JWT auth.
     *
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create a new authentication guard.
     *
     * @param \Tymon\JWTAuth\JWTAuth $auth
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->auth->getToken();

        if (! empty($token)) {
            $user = $this->auth->toUser($token) ?: null;
        }

        return $this->user = $user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (($token = $this->auth->attempt($credentials)) === false) {
            return false;
        }

        $this->user = $this->auth->toUser($token);

        return true;
    }

    /**
     * Set the current request instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->auth->setRequest($request);

        return $this;
    }
}
