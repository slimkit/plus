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
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed as FeedRepository;

class FeedCollectionController extends Controller
{
    /**
     * 收藏动态.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, ResponseContract $response, FeedModel $feed)
    {
        $user = $request->user()->id;

        if ($feed->collected($user)) {
            return $response->json(['message' => '已经收藏过'])->setStatusCode(422);
        }

        $feed->collect($user);

        return $response->json(['message' => '收藏成功'])->setStatusCode(201);
    }

    /**
     * 取消收藏.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(Request $request, ResponseContract $response, FeedModel $feed)
    {
        $feed->uncollect(
            $request->user()->id
        );

        return $response->json(null, 204);
    }

    public function list(Request $request, FeedModel $feedModel, ResponseContract $response, FeedRepository $repository)
    {
        $user = $request->user();
        $current_user = $request->query('user', $user->id);
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);

        $feeds = $feedModel->join('feed_collections', function ($join) use ($current_user) {
            $join->on('feed_collections.feed_id', '=', 'feeds.id')->where('feed_collections.user_id', $current_user);
        })
        ->with([
            'pinnedComments' => function ($query) {
                return $query->limit(5);
            },
            // 需要获取软删除用户
            'user' => function ($query) {
                return $query->withTrashed();
            },
        ])
        ->select('feeds.*')
        ->orderBy('feed_collections.created_at', 'desc')
        ->offset($offset)
        ->limit($limit)
        ->get();

        return $response->json($feedModel->getConnection()->transaction(function () use ($feeds, $repository, $user) {
            return $feeds->map(function (FeedModel $feed) use ($repository, $user) {
                $repository->setModel($feed);
                $repository->images();
                $repository->format($user->id);
                $repository->previewComments();

                $feed->has_collect = $feed->collected($user->id);
                $feed->has_like = $feed->liked($user->id);

                return $feed;
            });
        }))->setStatusCode(200);
    }
}
