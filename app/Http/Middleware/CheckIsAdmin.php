<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\MessageResponseBody;
use Illuminate\Http\Request;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('is_admin') or !$request->session()->has('user_id') {
            return app(MessageResponseBody::class, [
                'code' => 1000,
            ])->setStatusCode(403);
        }
        die;
        return $next($request);
    }
}
