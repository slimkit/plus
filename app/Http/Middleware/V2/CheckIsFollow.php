<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Following;
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
        $following_user_id = $request->user->id;
        $user_id = $request->user()->id;
        if (Following::where([
                ['user_id', $user_id],
                ['following_user_id', $following_user_id],
            ])
            ->count()
        ) {
            return response()->json([
                'message' => '您已经关注了此用户',
            ])->setStatusCode(400);
        }

        return $next($request);
    }
}
