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

namespace Zhiyi\PlusGroup\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\PlusGroup\Models\Post;
use Zhiyi\PlusGroup\Models\GroupMember;
use Zhiyi\PlusGroup\Repository\Post as PostRepository;

class PostCollectionController
{
    /**
     * 收藏帖子.
     */
    public function store(Request $request, Post $post, GroupMember $member)
    {
        $user = $request->user();

        if (($post->group->mode !== 'public') && (! $member->isNormal($post->group_id, $user->id))) {
            return response()->json(['message' => '未加入该圈子或已被拉黑'], 403);
        }

        if ($post->collected($user)) {
            return response()->json(['message' => '已经收藏过'], 422);
        }

        $post->collect($user);

        return response()->json(['message' => '收藏成功'], 201);
    }

    /**
     * 取消收藏.
     */
    public function destroy(Request $request, Post $post)
    {
        $post->uncollect(
            $request->user()->id
        );

        return response()->json(null, 204);
    }

    /**
     * 用户收藏的帖子.
     *
     * @param Request $request
     * @param FeedModel $feedModel
     * @param ResponseContract $response
     * @param FeedRepository $repository
     * @return mixed
     */
    public function collections(Request $request, Post $post, PostRepository $repository)
    {
        $user = $request->user();

        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);

        $posts = $post->whereHas('collectors', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['user', 'images', 'group'])
        ->limit($limit)
        ->offset($offset)
        ->get();

        $items = $posts->map(function ($post) use ($user, $repository) {
            $repository->formatCommonList($user, $post);

            return $post;
        });

        return response()->json($items, 200);
    }
}
