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

use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\API2\Requests\ListAllComments;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\API2\Resources\Comment as CommentResource;

class Comment extends Controller
{
    /**
     * List all comments.
     * @param \Zhiyi\Plus\API2\Requests\ListAllComments $request
     * @param \Zhiyi\Plus\Models\Comment $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ListAllComments $request, CommentModel $model): JsonResponse
    {
        // Skip non id where?
        $skipNonId = false;

        // Get database query `id` direction.
        $direction = $request->query('direction', 'desc');

        // Query comments.
        $comments = $model

            // New a database query.
            ->query()

            // 如果存在 `id` 查询字段，进入查新条件构建
            // 如果是空字段，则继续，反之设置 `$skipNonId = true` 跳过后面的条件
            ->when($id = $request->query('id'), function ($query) use ($id, &$skipNonId) {
                $id = array_values(array_filter(explode(',', $id)));
                if (! $id) {
                    return $query;
                } elseif (count($id) === 1) {
                    $skipNonId = true;

                    return $query->where('id', array_pop($id));
                }

                $skipNonId = true;

                return $query->whereIn('id', $id);
            })

            // 如果传递了数据开始标记，进入查询构建，
            // 如果  `$skipNonId` 为 true 则跳过
            ->when(($index = $request->query('index') && ! $skipNonId), function ($query) use ($index, $direction) {
                return $query->where('id', $direction === 'desc' ? '<' : '>', $index);
            })
            ->when(($author = $request->query('author')) && ! $skipNonId, function ($query) use ($author) {
                return $query->where('user_id', $author);
            })
            ->when(($forUser = $request->query('for_user')) && ! $skipNonId, function ($query) use ($forUser, $request) {
                $forType = $request->query('for_type', 'all');

                if ($forType === 'target') {
                    return $query->where('target_user', $forUser);
                } elseif ($forType === 'reply') {
                    return $query->where('reply_user', $forUser);
                }

                return $query->where(function ($query) use ($forUser) {
                    return $query
                        ->where('target_user', $forUser)
                        ->orWhere('reply_user', $forUser);
                });
            })
            ->when(($resourceableId = $request->query('resourceable_id')) && ! $skipNonId, function ($query) use ($resourceableId, $request) {
                $resourceableId = array_values(array_filter(explode(',', $resourceableId)));
                if (! $resourceableId) {
                    return $query;
                }

                return $query
                    ->where('commentable_type', $request->query('resourceable_type'))
                    ->whereIn('commentable_id', $resourceableId);
            })
            ->when(! $skipNonId, function ($query) use ($request) {
                return $query->limit($request->query('limit', 15));
            })
            ->orderBy('id', $direction)
            ->get();

        return CommentResource::collection($comments)
            ->toResponse($request)
            ->setStatusCode(JsonResponse::HTTP_OK);
    }
}
