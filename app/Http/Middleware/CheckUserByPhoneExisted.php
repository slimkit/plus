<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\User;
use Closure;

class CheckUserByPhoneExisted
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

        // 用户不存在
        if (!$user) {
            return app(MessageResponseBody::class, [
                'code' => 1005
            ]);
        }

        $request->attributes->set('user', $user);

        return $next($request);
    }
}
