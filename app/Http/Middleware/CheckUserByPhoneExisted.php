<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\User;
use Closure;

class CheckUserByPhoneExisted
{
    protected $types = [
        'register',
        'login',
        'change',
    ];

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
        $type = $request->input('type');

        if (!in_array($type, $this->types)) {
            return app(MessageResponseBody::class, [
                'code' => 1011,
            ]);
        }

        // 查询包含软删除的用户～
        $user = User::byPhone($phone)->withTrashed()->first();

        // 如果是注册， 存在用户则返回错误
        if ($type == 'register' && $user) {
            return app(MessageResponseBody::class, [
                'code' => 1010,
            ]);

        // 如果是登录或者修改用户资料等
        } elseif (in_array($type, ['login', 'change']) && !$user) {
            return app(MessageResponseBody::class, [
                'code' => 1012,
            ]);
        } else if (($type == 'register' && !$user) || (in_array($type, ['login', 'change']) && $user)) {
            return $next($request);
        }

        return app(MessageResponseBody::class, [
            'code' => $type == 'register' ? 1010 : 1012,
        ]);
    }
}
