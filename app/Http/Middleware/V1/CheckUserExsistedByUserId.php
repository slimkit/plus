<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class CheckUserExsistedByUserId
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
        if (! $request->user_id) {
            return response()->json(static::createJsonData([
                'code'    => 1018,
                'message' => '目标用户user_id不能为空',
                'status'  => false,
            ]))->setStatusCode(400);
        }
        $user = User::find($request->user_id);
        if (! $user) {
            return response()->json(static::createJsonData([
                'code'    => 1019,
                'message' => '目标用户不存在',
                'status'  => false,
            ]))->setStatusCode(404);
        }
        if ($request->user()->id == $request->user_id) {
            return response()->json(static::createJsonData([
                'code'    => 1022,
                'message' => '不能对自己进行关注相关操作',
                'status'  => false,
            ]))->setStatusCode(400);
        }

        return $next($request);
    }
}
