<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class CheckIsFollow
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
    public function handle(Request $request, Closure $next)
    {
        $following_user_id = $request->user_id;
        $target_user = User::find($request->user_id);
        $user = $request->user();

        if ($user->hasFollwing($target_user)) {
            return response()->json(static::createJsonData([
                'code'    => 1020,
                'message' => '您已经关注了此用户',
                'status'  => false,
            ]))->setStatusCode(400);
        }

        return $next($request);
    }
}
