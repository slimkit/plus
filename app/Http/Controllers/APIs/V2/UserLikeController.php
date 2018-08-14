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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Like as LikeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

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
