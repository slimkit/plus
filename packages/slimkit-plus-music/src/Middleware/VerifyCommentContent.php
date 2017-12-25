<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Middleware;

use Closure;
use Illuminate\Http\Request;
use Zhiyi\Plus\Traits\CreateJsonResponseData;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Feed;

class VerifyCommentContent
{
    use CreateJsonResponseData;
    
    /**
     * 验证评论内容是否存在.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        if (!$request->input('comment_content')) {
            return response()->json([
                'status' => false,
                'code' => 8005,
                'message' => '评论内容不能为空'
            ])->setStatusCode(400);
        }
        
        return $next($request);
    }
}
