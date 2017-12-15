<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedAtme;

class FeedAtmeController extends Controller
{
    /**
     * 获取@我的分享列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getAtmeList(Request $request)
    {
        $user = $request->user()->id;
        $limit = $request->get('limit', 10);

        $list = FeedAtme::ByAtUserId($user)->take($limit)->where(function ($query) use ($request) {
            if (intval($request->max_id) > 0) {
                $query->where('id', '<', intval($request->max_id));
            }
        })->with('feed')->orderBy('id', 'desc')->get();

        return response()->json(static::createJsonData([
            'code'   => 0,
            'status' => true,
            'data' => $list,
        ]))->setStatusCode(200);
    }
}
