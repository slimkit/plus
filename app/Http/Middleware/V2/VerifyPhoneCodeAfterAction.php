<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

use Closure;
use Zhiyi\Plus\Models\VerifyCode;

class VerifyPhoneCodeAfterAction
{
    /**
     * 验证验证码后置中间件.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $status = $response->status();

        if ($status === 201 || $status === 200 || $status === 204) {
            $phone = $request->input('phone');
            $code = $request->input('code');

            $vaild = 300;
            $verify = VerifyCode::byAccount($phone)
                ->byValid($vaild)
                ->byCode($code)
                ->orderByDesc()
                ->update(['state' => 2]);
        }

        return $response;
    }


}
