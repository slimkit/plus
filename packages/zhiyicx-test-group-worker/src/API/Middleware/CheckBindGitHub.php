<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class CheckBindGitHub
{
    protected $auth;

    /**
     * Create the middleware.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * The middleware handle.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            return response()->json(['message' => '请登录'], 401);
        }

        $user = $this->auth->user();
        if (! $user->githubAccess) {
            return response()->json(['message' => '请先绑定 GitHub 账号'], 403);
        }

        return $next($request);
    }
}
