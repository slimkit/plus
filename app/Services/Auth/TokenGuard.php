<?php

namespace Zhiyi\Plus\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;

class TokenGuard implements Guard
{
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new authentication guard.
     *
     * @param \Zhiyi\Plus\Services\Auth\TokenUserProvider $provider
     * @param \Illuminate\Http\Request                    $request
     *
     * @return void
     */
    public function __construct(TokenUserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
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

        $token = $this->getTokenForRequest();

        if (! empty($token)) {
            $user = $this->provider->retrieveByCredentials(
                ['token' => $token]
            );
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
        // The test useing code.
        // Do not know the use.
        dd($credentials);
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        // APIs v1.
        if ($this->request->is('*/v1/*')) {
            return $this->request->header(
                'ACCESS-TOKEN',
                $this->request->header('Authorization', '')
            );
        }
        if ($token = $this->request->bearerToken()) {
            return $token;
        }

        return $this->request->query('access_token', '');
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
        $this->request = $request;

        return $this;
    }
}
