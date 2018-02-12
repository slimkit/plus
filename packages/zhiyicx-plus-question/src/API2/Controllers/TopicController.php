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

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TopicController extends Controller
{
    /**
     * Get all topocs.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Topic $topicModel
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request,
                          ResponseFactoryContract $response,
                          TopicModel $topicModel)
    {
        $limit = min(50, max(1, intval($request->query('limit', 15))));
        $offset = $request->query('offset', 0);
        $name = $request->query('name', false);
        $follow = $request->query('follow', false);
        $userId = $request->user('api')->id ?? 0;

        $topics = $topicModel->when($name, function (Builder $query) use ($name) {
            return $query->where('name', 'like', '%'.$name.'%');
        })
        ->orderBy('sort', 'desc')
        ->orderBy('follows_count', 'desc')
        ->limit($limit)
        ->offset($offset)
        ->get();

        if ($userId && $follow) {
            $topics->load(['followers' => function (BelongsToMany $query) use ($userId) {
                $query->wherePivot('user_id', $userId);
            }]);
        }

        return $response->json($follow ? $topics->map(function (TopicModel $topic) {
            $topic->has_follow = false;
            if (
                $topic->relationLoaded('followers') &&
                $topic->followers->isNotEmpty()
            ) {
                $topic->has_follow = true;
            }

            return $topic->setRelations([]);
        }) : $topics)->setStatusCode(200);
    }

    /**
     * Get a single topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Topic $topic
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseFactoryContract $response, TopicModel $topic)
    {
        $userId = $request->user('api')->id ?? 0;
        $loadMap = ['experts' => function ($query) {
            $query->limit(5);
        }];

        if ($userId) {
            $loadMap['followers'] = function ($query) use ($userId) {
                $query->where('user_id', $userId);
            };
        }

        $topic->addHidden('followers');
        $topic->load($loadMap);
        $topic->has_follow = false;

        if ($topic->relationLoaded('followers') && $topic->followers->isNotEmpty()) {
            $topic->has_follow = true;
        }

        return $response->json($topic, 200);
    }
}
