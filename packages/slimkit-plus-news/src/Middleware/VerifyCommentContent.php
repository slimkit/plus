<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Middleware;

use Closure;
use Illuminate\Http\Request;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class VerifyCommentContent
{
    use CreateJsonResponseData;

    /**
     * check the comment_content.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->input('comment_content')) {
            return response()->json([
                'status' => false,
                'code' => 9002,
                'message' => '评论内容不能为空',
            ])->setStatusCode(400);
        }

        return $next($request);
    }
}
