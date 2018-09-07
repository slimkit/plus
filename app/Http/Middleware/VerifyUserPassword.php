<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        if ($this->auth->guest()) {
            throw new UnauthorizedHttpException('请先登录');
        } elseif ($this->auth->getProvider()->validateCredentials(
            $this->auth->user(),
            [
                'password' => $request->input('password')
            ]
        )) {
            return $next($request);
        }

        throw new AccessDeniedHttpException('密码验证错误');
    }
}
