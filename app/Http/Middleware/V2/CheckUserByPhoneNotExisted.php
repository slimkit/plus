<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

use Closure;
use Zhiyi\Plus\Models\User;

class CheckUserByPhoneNotExisted
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $phone = $request->input('phone');
        $user = User::byPhone($phone)->withTrashed()->first();

        // 手机号已被使用
        if ($user) {
            return response()->json([
                'message' => '手机号已被使用',
            ])->setStatusCode(400);
        }

        return $next($request);
    }
}
