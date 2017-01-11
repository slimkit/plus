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
        $session = $request->session()->all();
        if (!$session['is_admin'] or !$session['user_id']) {
            if (!isset($_SERVER['X-Requested-With'])) {
                return redirect(route('admin.login'));
            }

            return app(MessageResponseBody::class, [
                'code'    => 5001,
                'message' => '你不是管理员',
                'data' => [
                    // 后台非管理员访问先跳转到管理员登录页
                    'jumpUrl' => 'login'
                ]
            ])->setStatusCode(403);
        }

        return $next($request);
    }
}
