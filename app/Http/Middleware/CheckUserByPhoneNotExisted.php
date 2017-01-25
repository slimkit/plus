<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\User;
use Closure;
use Ts\Traits\CreateJsonResponseData;

class CheckUserByPhoneNotExisted
{
    use CreateJsonResponseData;
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
        $user = User::byPhone($phone)->withTrashed()->first();

        // 手机号已被使用
        if ($user) {

            return response()->json(static::createJsonData([
                'code' => 1010,
            ]))->setStatusCode(403);
        }

        return $next($request);
    }
}
