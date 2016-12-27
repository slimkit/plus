<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\User;
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
        $phone = $request->input('phone');
        $password = $request->input('password');
        if ($request->attributes->has('user')) {
            $user = $request->attributes->get('user');
        } else {
            $user = User::byPhone($phone)->first();
        }

        if (!$user->verifyPassword($password)) {
            return app(MessageResponseBody::class, [
                'code' => 1006,
            ]);
        }

        $request->attributes->set('user', $user);

        return $next($request);
    }
}
