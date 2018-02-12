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
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TopicUserController extends Controller
{
    /**
     * Get all topics of the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $limit = min(50, max(1, intval($request->query('limit', 15))));
        $after = $request->query('after', false);
        $type = in_array(($type = $request->query('type', 'follow')), ['follow', 'expert']) ? $type : 'follow';
        $methodMap = [
            'follow' => 'questionTopics',
            'expert' => 'belongTopics',
        ];

        $topics = $user->{$methodMap[$type]}()
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        return $response->json($topics, 200);
    }

    /**
     * Follow a topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Topic $topic
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, ResponseFactoryContract $response, TopicModel $topic)
    {
        $user = $request->user();

        if ($user->questionTopics()->newPivotStatementForId($topic->id)->first()) {
            $message = trans('plus-question::topics.followed', ['name' => $topic->name]);

            return $response->json(['message' => [$message]], 422);
        }

        $topic->getConnection()->transaction(function () use ($topic, $user) {
            $topic->increment('follows_count', 1);
            $user->questionTopics()->attach($topic);
        });

        return $response->json(['message' => [trans('plus-question::messages.success')]], 201);
    }

    /**
     * Unfollow a topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Topic $topic
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(Request $request, ResponseFactoryContract $response, TopicModel $topic)
    {
        $user = $request->user();

        if (! $user->questionTopics()->newPivotStatementForId($topic->id)->first()) {
            $message = trans('plus-question::topics.not-follow', ['name' => $topic->name]);

            return $response->json(['message' => [$message]], 422);
        }

        $topic->getConnection()->transaction(function () use ($topic, $user) {
            $topic->decrement('follows_count', 1);
            $user->questionTopics()->detach($topic);
        });

        return $response->make(null, 204);
    }
}
