<?php

namespace Zhiyi\Plus\Http\Middleware;

use Closure;

class SensitiveOperation
{
    /**
     * 对敏感操作验证用户密码是否正确.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (! $request->has('password') || ! $user->verifyPassword($request->input('password'))) {

            return response()->json(['message' => ['账户验证失败']], 403);
        }

        return $next($request);
    }
}