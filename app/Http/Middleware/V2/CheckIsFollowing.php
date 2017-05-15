<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Following;

class CheckIsFollowing
{
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
        $user_id = $request->user()->id;
        $following_user_id = $request->user->id;
        if (! Following::where([
                ['user_id', $user_id],
                ['following_user_id', $following_user_id],
            ])
            ->count()
        ) {
            return response()->json([
                'message' => '您并没有关注此用户',
            ])->setStatusCode(400);
        }

        return $next($request);
    }
}
