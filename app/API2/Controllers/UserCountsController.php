<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\API2\Controllers;

use Illuminate\Http\JsonResponse;
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
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function count(Request $request): JsonResponse
    {
        $counts = UserCountModel::query()->where('user_id', $request->user()->id)->get();
        $counts = $counts->keyBy('type')->map(function ($count) {
            return $count->total;
        });

        return (new UserCountsResource($counts->all()))
            ->response()
            ->setStatusCode(200);
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
        $type = $request->input('type');
        if ($type && in_array($type, ['commented', 'liked', 'system', 'group-post-pinned', 'post-comment-pinned', 'feed-comment-pinned', 'news-comment-pinned', 'post-pinned', 'mutual', 'following', 'group-join-pinned'])) {
            $type = 'user-'.$type;
        }

        UserCountModel::where('user_id', $request->user()->id)
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->update([
                'total' => 0,
                'read_at' => new Carbon(),
            ]);

        return response()->json('', 204);
    }
}
