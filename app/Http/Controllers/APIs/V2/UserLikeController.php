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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Like as LikeModel;

class UserLikeController extends Controller
{
    public function index(Request $request, ResponseContract $response, LikeModel $model)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after', false);
        $user = $request->user();

        $likes = $model->with([
            'likeable',
            'user' => function ($query) {
                return $query->withTrashed();
            },
        ])
            ->where('target_user', $user->id)
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->where('user_id', '!=', $user->id)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        if ($user->unreadCount !== null) {
            $user->unreadCount()->decrement('unread_likes_count', $user->unreadCount->unread_likes_count);
        }

        return $response->json($likes)->setStatusCode(200);
    }
}
