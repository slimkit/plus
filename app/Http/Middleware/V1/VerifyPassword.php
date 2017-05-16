<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class VerifyPassword
{
    use CreateJsonResponseData;

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
            return response()->json(static::createJsonData([
                'code' => 1006,
            ]))->setStatusCode(401);
        }

        return $next($request);
    }
}
