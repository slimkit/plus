<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Illuminate\Http\Request;

class ChangeUsername
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
        $username = $request->input('name');
        if ($username) {
            return app(VerifyUserNameRole::class)->handle($request, function (Request $request) use ($next, $username) {
                return app(CheckUserByNameNotExisted::class)->handle($request, function (Request $request) use ($next, $username) {
                    $user = $request->user();
                    $user->name = $username;
                    $user->save();

                    return $next($request);
                });
            });
        }

        return $next($request);
    }
}
