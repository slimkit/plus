<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use function Zhiyi\Plus\setting;
use Illuminate\Contracts\Auth\Guard;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class VerifyUserPassword
{
    /**
     * The user guard.
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * The middleware handler.
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        if (setting('pay', 'validate-password', false) === false) {
            return $next($request);
        } elseif ($this->auth->guest()) {
            throw new UnauthorizedHttpException('请先登录');
        } elseif ($this->auth->getProvider()->validateCredentials(
            $this->auth->user(),
            [
                'password' => $request->input('password'),
            ]
        )) {
            return $next($request);
        }

        throw new AccessDeniedHttpException('密码验证错误');
    }
}
