<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Zhiyi\Plus\Models\VerifyCode;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class VerifyPhoneCode
{
    use CreateJsonResponseData;

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
            return response()->json(static::createJsonData([
                'code' => 1001,
            ]))->setStatusCode(403);
        }

        // 验证通过，失效验证码，执行下一步操作.
        $verify->state = 2;
        $verify->save();

        return $next($request);
    }
}
