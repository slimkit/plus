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

 use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Http\JsonResponse;
 use Symfony\Component\HttpFoundation\Response;
 use Zhiyi\Plus\API2\Controllers\Controller;
 use Zhiyi\Plus\API2\Requests\Feed\TopicIndex as IndexRequest;
 use Zhiyi\Plus\API2\Resources\Feed\TopicCollection;
 use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;

 class Topic extends Controller
 {

    /**
     * List topics.
     * 
     * @param \Zhiyi\Plus\Requests\Feed\TopicIndex $request
     * @param \Zhiyi\Plus\Models\FeedTopic $model
     * @return //
     */
    public function index(IndexRequest $request, FeedTopicModel $model): JsonResponse
    {
        // Get query data `id` order direction.
        // Value: `asc` or `desc`
        $direction = $request->query('direction', 'desc');

        // Query database data.
        $result = $model
            ->query()

            // If `$request->query('q')` param exists,
            // create "`name` like %?%" SQL where.
            ->when((bool) ($searchKeyword = $request->query('q', false)), function (EloquentBuilder $query) use ($searchKeyword) {
                return $query->where('name', 'like', sprintf('%%%s%%', $searchKeyword));
            })

            // If `$request->query('index)` param exists,
            // using `$direction` create "id ? ?" where
            //     ?[0] `$direction === asc` is `>`
            //     ?[0] `$direction === desc` is `<`
            ->when((bool) ($indexID = $request->query('index', false)), function (EloquentBuilder $query) use ($indexID, $direction) {
                return $query->where('id', $direction === 'asc' ? '>' : '<', $indexID);
            })

            // Set the number of data
            ->limit($request->query('limit', 15))

            // Using `$direction` set `id` direction,
            // the `$direction` enum `asc` or `desc`.
            ->orderBy('id', $direction)

            // Set only query table column name.
            ->select('id', 'name', 'logo', Model::CREATED_AT)

            // Run the SQL query, return a collection.
            // instanceof \Illuminate\Support\Collection
            ->get();
        
        // Create the action response.
        $response = (new TopicCollection($result))
            ->response()
            ->setStatusCode(Response::HTTP_OK /* 200 */);
        
        return $response;
    }
 }
