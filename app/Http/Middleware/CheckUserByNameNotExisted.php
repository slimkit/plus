<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckUserByNameNotExisted
{
    /**
     * 从检查用户是否不存在中间件.
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

        // 用户名已被使用
        if ($user) {
            return app(MessageResponseBody::class, [
                'code' => 1004,
            ])->setStatusCode(403);
        }

        return $next($request);
    }
}
