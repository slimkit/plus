<?php

namespace Zhiyi\Plus\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminLogin
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
        $session = $request->session()->all();
        if (isset($session['user_id']) && $session['user_id'] && isset($session['is_admin']) && $session['is_admin']) {
            return redirect('/admin/');
        }

        return $next($request);
    }
}
