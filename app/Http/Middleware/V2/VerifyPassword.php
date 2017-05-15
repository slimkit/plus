<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

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
        $user = $request->user();

        if (! $user->verifyPassword($password)) {
            return response()->json([
                'message' => '用户密码错误',
            ])->setStatusCode(401);
        }

        return $next($request);
    }
}
