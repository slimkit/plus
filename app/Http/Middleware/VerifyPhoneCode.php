<?php

namespace App\Http\Middleware;

use App\Models\VerifyCode;
use App\Exceptions\MessageResponseBody;
use Closure;

class VerifyPhoneCode
{
    /**
     * 验证验证码中间件.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $phone = $request->input('phone');

        $vaild = 300;
        $verify = VerifyCode::byAccount($phone)->byValid($vaild)->orderByDesc()->first();

        if (!$verify || $verify->state == 2) {
            return app(MessageResponseBody::class, [
                'code' => 1001,
            ]); 
        }

        // 验证通过，失效验证码，执行下一步操作.
        $verify->state = 2;
        $verify->save();

        return $next($request);
    }
}
