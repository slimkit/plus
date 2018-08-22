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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Services\Push;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;

class LikeController extends Controller
{
    /**
     * Get feed likes.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseContract $response, FeedModel $feed)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after', false);
        $userID = $request->user('api')->id ?? 0;
        $likes = $feed->likes()
            ->whereHas('user')
            ->with(['user' => function ($query) {
                return $query->withTrashed();
            }])
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json(
            $feed->getConnection()->transaction(function () use ($likes, $userID) {
                return $likes->map(function ($like) use ($userID) {
                    if (! $like->relationLoaded('user')) {
                        return $like;
                    }

                    $like->user->following = false;
                    $like->user->follower = false;

                    if ($userID && $like->user_id !== $userID) {
                        $like->user->following = $like->user->hasFollwing($userID);
                        $like->user->follower = $like->user->hasFollower($userID);
                    }

                    return $like;
                });
            })
        )->setStatusCode(200);
    }

    /**
     * 用户点赞接口.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, ResponseContract $response, FeedModel $feed)
    {
        $user = $request->user();

        if ($feed->liked($user)) {
            return $response->json(['message' => '操作成功'])->setStatusCode(201);
        }

        $feed->like($user);

        if ($feed->user_id !== $user->id) {
            // 添加被赞的未读数
            $feed->user->unreadCount()->firstOrCreate([])->increment('unread_likes_count', 1);
            // 新未读统计 1.8启用
            $userLikedCount = UserCountModel::firstOrNew([
                'type' => 'user-liked',
                'user_id' => $feed->user->id,
            ]);

            $userLikedCount->total += 1;
            $userLikedCount->save();

            app(Push::class)->push(sprintf('%s 点赞了你的动态', $user->name), (string) $feed->user->id, ['channel' => 'feed:digg']);
        }

        return $response->json(['message' => '操作成功'])->setStatusCode(201);
    }

    /**
     * 取消动态赞.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(Request $request, ResponseContract $response, FeedModel $feed)
    {
        $user = $request->user();

        if (! $feed->liked($user)) {
            return $response->json(['message' => '操作成功'])->setStatusCode(204);
        }

        $feed->unlike($user);

        return $response->json(['message' => '操作成功'])->setStatusCode(204);
    }
}
