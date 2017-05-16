<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Validator;

class CheckDeviceCodeExisted
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
        $validator = Validator::make($request->all(), [
            'device_code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'code'    => 1014,
                'message' => '设备号不能为空',
                'data'    => null,
            ])->setStatusCode(422);
        }

        return $next($request);
    }
}
