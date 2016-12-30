<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\User;
use Closure;
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
        if (!$user || $user->deleted_at) {
            return app(MessageResponseBody::class, [
                'code' => 1005,
            ]);
        }

        $request->attributes->set('user', $user);

        return $next($request);
    }
}
