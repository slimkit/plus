<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

use Closure;
use Zhiyi\Plus\Models\VerifyCode;

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

        if (! $verify || $verify->state == 2) {
            return response()->json([
                '验证码错误或失效',
            ])->setStatusCode(403);
        }

        // 验证通过，失效验证码，执行下一步操作.
        $verify->state = 2;
        $verify->save();

        return $next($request);
    }
}
