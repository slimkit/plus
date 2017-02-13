<?php

namespace Zhiyi\Plus\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Role
{
    const DELIMITER = '|';

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
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if (!is_array($roles)) {
            $roles = explode(static::DELIMITER, $roles);
        }

        var_dump($request->user());
        var_dump($roles);
        exit;

        // ...

        return $next($request);
    }
}
