<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use Closure;

class VeridySendPhoneCodeType
{
    protected $types = [
        'register',
        'login',
        'change',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $type = $request->input('type');

        // 传递获取类型不正确
        if (!in_array($type, $this->types)) {
            return app(MessageResponseBody::class, [
                'code' => 1011,
            ]);

        // 如果是注册获取验证码，如果用户不存在继续执行
        } else if ($type == 'register') {
            return app(CheckUserByPhoneNotExisted::class)->handle($request, $next);
        }

        // 如果不是注册，用户存在则继续执行
        return app(CheckUserByPhoneExisted::class)->handle($request, $next);
    }
}
