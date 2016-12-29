<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\VerifyCode;
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
        $code = (int) $request->input('code');

        $vaild = 300;
        $verify = VerifyCode::byAccount($phone)
            ->byValid($vaild)
            ->byCode($code)
            ->orderByDesc()
            ->first();

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
