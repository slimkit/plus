<?php

namespace Zhiyi\Plus\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return ($redirectTo = $this->redirectTo())
                ? redirect($redirectTo)
                : back();
        }

        return $next($request);
    }

    /**
     * logged in redirect path.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function redirectTo(): string
    {
        return request()->input('redirect') ?: '';
    }
}
