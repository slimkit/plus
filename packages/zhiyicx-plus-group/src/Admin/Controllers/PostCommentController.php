<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\PlusGroup\Admin\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment as CommentModel;

class PostCommentController
{
    /**
     * 帖子评论列表.
     *
     * @param Request $request
     * @param CommentModel $comment
     * @return mixed
     */
    public function index(Request $request, CommentModel $comment)
    {
        $end = $request->query('end');
        $user = $request->query('user');
        $start = $request->query('start');
        $postId = $request->query('post_id');
        $keyword = $request->query('keyword');

        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 15);

        $query = $comment->where('commentable_type', 'group-posts')
        ->when($postId, function ($query) use ($postId) {
            return $query->where('commentable_id', $postId);
        })
        ->when($start || $end, function ($query) use ($start, $end) {
            if ($start) {
                $query->where('created_at', '>=', Carbon::parse($start)->startOfDay());
            }
            if ($end) {
                $query->where('created_at', '<', Carbon::parse($end)->endOfDay());
            }

            return $query;
        })
        ->when($user, function ($query) use ($user) {
            if (is_numeric($user)) {
                return $query->where('user_id', $user);
            } else {
                return $query->whereHas('user', function ($query) use ($user) {
                    return $query->where('name', 'like', sprintf('%%%s%%', $user));
                });
            }
        })
        ->when($keyword, function ($query) use ($keyword) {
            return $query->where('body', 'like', sprintf('%%%s%%', $keyword));
        });

        $count = $query->count();

        $items = $query->orderBy('id', 'desc')
        ->with(['user', 'reply', 'target'])
        ->offset($offset)
        ->limit($limit)
        ->get();

        return response()->json($items, 200, ['x-total' => $count]);
    }

    /**
     * 删除评论.
     *
     * @param CommentModel $comment
     * @return mixed
     */
    public function delete(CommentModel $comment)
    {
        $comment->delete();

        return response()->json(null, 204);
    }
}
