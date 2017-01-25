<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Ts\Traits\CreateJsonResponseData;

class CheckIsAdmin
{
    use CreateJsonResponseData;

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

            return response()->json(static::createJsonData([
                'code'    => 5001,
                'message' => '你不是管理员',
                'data'    => [
                    'jumpUrl' => 'login',
                ],
            ]))->setStatusCode(403);
        }

        return $next($request);
    }
}
