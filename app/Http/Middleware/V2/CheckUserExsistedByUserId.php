<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;

class CheckUserExsistedByUserId
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
        if ($request->user()->id == $request->user->id) {
            return response()->json([
                'message' => '不能对自己进行相关操作',
            ])->setStatusCode(400);
        }

        return $next($request);
    }
}
