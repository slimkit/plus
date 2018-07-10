<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\API2\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\Plus\API2\Resources\UserCountsResource;
use Zhiyi\Plus\Models\UserCount as UserCountModel;

class UserCountsController extends Controller
{
    /**
     * Create the Controller instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * The route controller to callable handle.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Zhiyi\Plus\API2\Resources\UserCountsResource
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function count(Request $request): UserCountsResource
    {
        $user = $request->user();
        $counts = UserCountModel::where('user_id', $user->id)->get();
        // $now = new Carbon();
        $data = [];
        $counts->each(function (UserCountModel $count) use (&$data) {
            $data[$count->type] = $count->total;
        });

        return new UserCountsResource($data);
    }

    /**
     * 重置某项为度数为0.
     * @Author   Wayne
     * @DateTime 2018-04-16
     * @Email    qiaobin@zhiyicx.com
     * @param    Request             $request [description]
     * @return   [type]                       [description]
     */
    public function reset(Request $request)
    {
        $type = $request->input('type', '');
        if (! in_array($type, ['commented', 'liked', 'system', 'group-post-pinned', 'post-comment-pinned', 'feed-comment-pinned', 'news-comment-pinned', 'post-pinned', 'mutual', 'following', 'group-join-pinned'])) {
            return response()->json(['message' => '非法请求'], 422);
        }
        $user = $request->user();
        $now = new Carbon();
        UserCountModel::where('type', 'user-'.$type)
            ->where('user_id', $user->id)
            ->update([
                'total' => 0,
                'read_at' => $now,
            ]);

        return response()->json('', 204);
    }
}
