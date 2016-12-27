<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\User;
use Closure;

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
            return app(MessageResponseBody::class, [
                'code' => 1010,
            ]);
        }

        return $next($request);
    }
}
