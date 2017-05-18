<?php

namespace Zhiyi\Plus\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class RolePermissions
{
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $permissions
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle(Request $request, Closure $next, string $permissions, string $message = '')
    {
        if ($this->auth->guest() || ! $request->user()->can($permissions)) {
            abort(403, $message ?: '你没有权限执行该操作');
        }

        return $next($request);
    }
}
