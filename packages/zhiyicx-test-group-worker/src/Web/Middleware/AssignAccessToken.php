<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

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
