<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Web\Middleware;

use Closure;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class AssignAccessToken
{
    protected $jwt;
    protected $auth;

    /**
     * Create the middleware.
     *
     * @param \Tymon\JWTAuth\JWT $jwt
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(JWT $jwt, Guard $auth)
    {
        $this->jwt = $jwt;
        $this->auth = $auth;
    }

    /**
     * The handle method.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle($request, Closure $next)
    {
        if ($request instanceof Request) {
            $this->assign($request);
        }

        return $next($request);
    }

    /**
     * Assign access token.
     *
     * @param \Illuminate\Http\Request &$request
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function assign(Request &$request)
    {
        if ($this->auth->guest()) {
            abort(401, '请登录');
        }

        $accessToken = $request->session()->get('access_token');
        if (! $accessToken || ! $this->jwt->setToken($accessToken)->check()) {
            $accessToken = $this->jwt->fromUser($this->auth->user());
            $request->session()->put('access_token', $accessToken);
            $request->session()->save();
        }
    }
}
