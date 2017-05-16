<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

use Closure;

class VerifySendPhoneCodeType
{
    protected $types = [
        'register',
        'login',
        'change',
    ];

    /**
     * 验证获取验证码类型以及是否有权限发送中间件.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $type = $request->input('type');

        // 传递获取类型不正确
        if (! in_array($type, $this->types)) {
            return response()->json([
                'message' => '类型错误',
            ])->setStatusCode(400);

        // 如果是注册获取验证码，如果用户不存在继续执行
        } elseif ($type == 'register') {
            return app(CheckUserByPhoneNotExisted::class)->handle($request, $next);
        }

        // 如果不是注册，用户存在则继续执行
        return app(CheckUserByPhoneExisted::class)->handle($request, $next);
    }
}
