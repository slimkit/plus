<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Illuminate\Http\Request;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class CheckFeedbackContentExisted
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
        if (! $request->input('content')) {
            return response()->json(static::createJsonData([
                'code'    => 7001,
                'message' => '反馈内容不能为空',
                'status'  => false,
            ]))->setStatusCode(400);
        }

        return $next($request);
    }
}
