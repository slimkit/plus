<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Middleware;

use Closure;
use Illuminate\Http\Request;
use Zhiyi\Plus\Traits\CreateJsonResponseData;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class CheckFeedByFeedId
{
    use CreateJsonResponseData;

    /**
     * 验证动态是否存在.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $feed_id = intval($request->input('feed_id'));

        if (! $feed_id) {
            return response()->json(static::createJsonData([
                'code' => 6003,
            ]))->setStatusCode(400);
        }
        $feed = Feed::find($feed_id);
        if (! $feed) {
            return response()->json(static::createJsonData([
                'code' => 6004,
            ]))->setStatusCode(403);
        }
        $request->attributes->set('feed', $feed);

        return $next($request);
    }
}
