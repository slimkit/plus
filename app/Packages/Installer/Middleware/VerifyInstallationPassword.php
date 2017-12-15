<?php

namespace Zhiyi\Plus\Packages\Installer\Middleware;

use Closure;

class VerifyInstallationPassword
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle($request, Closure $next)
    {
        $password = $request->get('password');
        if (! $password) {
            abort(422, '请输入安装密码');
        } elseif (md5($password) !== config('installer.password')) {
            abort(422, '安装密码错误');
        }

        return $next($request);
    }
}
