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

namespace Zhiyi\Plus\API2\Controllers\Feed;

use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\Models\FeedTopicLink as FeedTopicLinkModel;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed as FeedRepository;
use Zhiyi\Plus\API2\Requests\Feed\ListFeedsForATopic as ListFeedsForATopicRequest;

class TopicFeed extends Controller
{
    /**
     * The list feeds for a topic action handle.
     */
    public function __invoke(ListFeedsForATopicRequest $request, FeedTopicLinkModel $model, FeedRepository $repository, int $topic): JsonResponse
    {
        $userID = $request->user('api')->id ?? 0;
        $direction = $request->query('direction', 'desc');
        $links = $model
            ->query()
            ->where('topic_id', $topic)
            ->when((bool) ($index = $request->query('index', false)), function (EloquentBuilder $query) use ($index, $direction): EloquentBuilder {
                return $query->where('index', $direction === 'asc' ? '>' : '<', $index);
            })
            ->select('index', 'feed_id')
            ->orderBy('index', $direction)
            ->limit($request->query('limit', 15))
            ->get();
        $links->load([
            'feed',
            'feed.topics' => function ($query) {
                return $query->select('id', 'name');
            },
            'feed.user' => function ($query) {
                return $query->withTrashed();
            },
        ]);

        $feeds = $links->map(function (FeedTopicLinkModel $link) use ($userID, $repository) {
            $feed = $link->feed;
            $feed->index = $link->index;

            $repository->setModel($feed);
            $repository->images();
            $repository->format($userID);
            $repository->previewComments();

            $feed->has_collect = $feed->collected($userID);
            $feed->has_like = $feed->liked($userID);

            return $feed;
        });

        return new JsonResponse($feeds, JsonResponse::HTTP_OK);
    }
}
