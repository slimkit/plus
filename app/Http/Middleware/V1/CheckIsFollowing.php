<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class CheckIsFollowing
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
        $user = $request->user();
        $following_user_id = $request->user_id;
        $target_user = User::find($request->user_id);
        if (! $user->hasFollwing($target_user)) {
            return response()->json(static::createJsonData([
                'code'    => 1021,
                'message' => '您并没有关注此用户',
                'status'  => false,
            ]))->setStatusCode(400);
        }

        return $next($request);
    }
}
