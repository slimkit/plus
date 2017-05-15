<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

use Closure;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;

class CheckUserByNameExisted
{
    /**
     * 检查用户是否存在中间件.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $name = $request->input('name');
        $user = User::byName($name)->withTrashed()->first();

        // 用户不存在 or 软删除用户
        if (! $user || $user->deleted_at) {
            return response()->json([
                'message' => '用户不存在或已删除',
            ])->setStatusCode(404);
        }

        return $next($request);
    }
}
