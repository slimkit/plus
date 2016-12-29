<?php

namespace App\Http\Middleware;

use Closure;
use Validator;
use App\Exceptions\MessageResponseBody;

class CheckDeviceCodeExisted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = Validator::make($request->all(), [
            'device_code' => 'required',
        ]);
        if ($validator->fails()) {
            return app(MessageResponseBody::class, [
                'code' => 1014,
                'message' => '设备号不能为空'
            ]);
        }

        return $next($request);
    }
}
