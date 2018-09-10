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
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Zhiyi\PlusGroup\Models\Post as PostModel;
use Zhiyi\PlusGroup\Models\Group as GroupModel;

class GroupPostController
{
    /**
     * 帖子列表.
     *
     * @param  Request $request
     * @param  int     $groupId
     * @return mixed
     */
    public function index(Request $request)
    {
        $user = $request->query('user');
        $type = $request->query('type');
        $title = $request->query('title');
        $pinned = $request->query('pinned');
        $groupId = $request->query('group_id');

        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);

        $data = $request->all();

        $query = PostModel::with(['user', 'latestPinned', 'group'])->has('group');

        $query->when($user, function ($query) use ($user) {
            return $query->whereHas('user', function ($query) use ($user) {
                return $query->where('name', 'like', sprintf('%%%s%%', $user));
            });
        })
        ->when($title, function ($query) use ($title) {
            return $query->where('title', 'like', sprintf('%%%s%%', $title));
        })
        ->when(! is_null($pinned), function ($query) use ($pinned) {
            return $query->whereHas('latestPinned', function ($query) use ($pinned) {
                return $query->where('status', $pinned);
            });
        })
        ->when($groupId, function ($query) use ($groupId) {
            return $query->where('group_id', $groupId);
        })->when($type && $type == 'trash', function ($query) {
            return $query->onlyTrashed();
        });

        $count = $query->count();
        $items = $query->limit($limit)->offset($offset)->get();

        $posts = $items->map(function ($item) {
            if ($item->latestPinned && $item->latestPinned->status == 1) {
                $item->latestPinned->expires_state = $item->latestPinned->expires_at > Carbon::now()->toDateTimeString();
            }

            return $item;
        });

        return response()->json($posts, 200, ['x-total' => $count]);
    }

    /**
     * Remove a post tu trash.
     * @param \Zhiyi\PlusGroup\Models\Group $group
     * @param \Zhiyi\PlusGroup\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function delete(GroupModel $group, PostModel $post): Response
    {
        if ($post->excellent_at) {
            $group->excellen_posts_count -= 1;
            $group->excellen_posts_count = $group->excellen_posts_count <= 0 ? 0 : $group->excellen_posts_count;
        }
        
        $group->posts_count -= 1;
        $group->posts_count = $group->posts_count <= 0 ? 0 : $group->posts_count;

        $group->getConnection()->transaction(function () use ($group, $post) {
            $post->delete();
            $group->save();
        });

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * 还原回收站帖子.
     * @param \Zhiyi\PlusGroup\Models\Post $model
     * @param int $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(PostModel $model, int $post): JsonResponse
    {
        $post = $model
            ->query()
            ->withTrashed()
            ->where('id', $post)
            ->first();
        
        if (! $post) {
            return new JsonResponse(['message' => '该帖子已被完全移除，无法恢复'], 422);
        } elseif (! ($group = $post->group)) {
            return new JsonResponse(['message' => '帖子所属圈子已被移除，无法恢复'], 422);
        }

        $group->excellen_posts_count = $post->excellent_at ? $group->excellen_posts_count + 1 : $group->excellen_posts_count;
        $group->posts_count += 1;
        $group->getConnection()->transaction(function () use ($group, $post) {
            $post->restore();
            $group->save();
        });

        return new JsonResponse(['message' => '恢复成功'], 201);
    }
}
