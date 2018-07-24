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

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TopicFollow extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Follow a topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\FeedTopic $model
     * @param int $topicID
     * @return \Illuminate\Http\Response
     */
    public function follow(Request $request, FeedTopicModel $model, int $topicID): Response
    {
        // Featch the request authentication user model.
        $user = $request->user();

        // Database query topic.
        $topic = $model
            ->query()
            ->where('id', $topicID)
            ->first();

        // If the topic Non-existent, throw a not found exception.
        if (! $topic) {
            throw new NotFoundHttpException('关注的话题不存在');
        }

        // Create success 204 response
        $response = (new Response())->setStatusCode(Response::HTTP_NO_CONTENT /* 204 */);

        // Database query the authentication user followed.
        $exists = $topic
            ->followers()
            ->where('user_id', $user->id)
            ->exists();
        if ($exists) {
            return $response;
        }

        return $user->getConnection()->transaction(function () use ($user, $topic, $response): Response {
            $topic->followers()->attach($user);
            $topic->followers_count += 1;
            $topic->save();

            return $response;
        });
    }
}
