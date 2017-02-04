<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class CheckUserByPhoneExisted
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

        // 用户不存在 or 软删除用户
        if (!$user || $user->deleted_at) {
            return resopnse()->json(static::createJsonData([
                'code' => 1005,
            ]))->setStatusCode(404);
        }

        $request->attributes->set('user', $user);

        return $next($request);
    }
}
