<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\User;
use Closure;

class VerifyPassword
{
    /**
     * 验证用户密码正确性中间件.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $password = $request->input('password', '');
        $user = $request->attributes->get('user');

        // 验证用户是否存在或者是否是一个user实例
        if (!$request->attributes->has('user') || !$user instanceof User) {
            return app(MessageResponseBody::class, [
                'code' => 1005,
            ])->setStatusCode(404);

        // 用户密码是否正确
        } elseif (!$user->verifyPassword($password)) {
            return app(MessageResponseBody::class, [
                'code' => 1006,
            ])->setStatusCode(401);
        }

        $request->attributes->set('user', $user);

        return $next($request);
    }
}
