<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use Closure;
use Illuminate\Http\Request;

class CheckIsAdmin
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
        if (!$request->session()->has('is_admin') or !$request->session()->has('user_id')) {
            return app(MessageResponseBody::class, [
                'code'    => 5001,
                'message' => '你不是管理员',
            ])->setStatusCode(403);
        }
        
        return $next($request);
    }
}
