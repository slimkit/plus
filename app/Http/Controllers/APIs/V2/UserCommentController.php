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
use Zhiyi\Plus\Models\Comment as CommentModel;

class UserCommentController extends Controller
{
    /**
     * Get user comments.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\Comment $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseContract $response, CommentModel $model)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = (int) $request->query('after', 0);

        $comments = $model->getConnection()->transaction(function () use ($user, $limit, $after, $model) {
            return $model->with([
                'commentable',
                'user' => function ($query) {
                    return $query->withTrashed();
                },
                'reply' => function ($query) {
                    return $query->withTrashed();
                },
                'target' => function ($query) {
                    return $query->withTrashed();
                },
            ])
                ->where(function ($query) use ($user) {
                    return $query->where('target_user', $user->id)
                        ->orWhere('reply_user', $user->id);
                })
                ->where('user_id', '!=', $user->id)
                ->when($after, function ($query) use ($after) {
                    return $query->where('id', '<', $after);
                })
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        });

        if ($user->unreadCount !== null) {
            $user->unreadCount()->decrement('unread_comments_count', $user->unreadCount->unread_comments_count);
        }
//        return $model->getConnection()->transaction(function () use ($comments, $response) {
        return $response->json($comments, 200);
//        });
    }
}
