<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class CheckUserByNameNotExisted
{
    use CreateJsonResponseData;

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
        $theUser = $request->attributes->get('user');

        // 用户名已被使用
        if (($user && (! $theUser)) || ($user && $theUser && $user->id != $theUser->id)) {
            return response()->json(static::createJsonData([
                'code' => 1004,
            ]))->setStatusCode(403);
        }

        return $next($request);
    }
}
