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
use Illuminate\Support\Carbon;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zhiyi\Plus\Models\FeedTopicUserLink as FeedTopicUserLinkModel;

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
            
        } elseif ($topic->users()->newPivotStatementForId($user->id)->exists()) {
            return (new Response())->setStatusCode(Response::HTTP_NO_CONTENT /* 204 */); 
        }

        $feedsCount = $topic->feeds()->where('user_id', $user->id)->count();

        return $user->getConnection()->transaction(function () use ($topic, $user, $feedsCount): Response {
            $topic->users()->attach($user, [
                'following_at' => new Carbon(),
                'feeds_count' => $feedsCount,
            ]);
            $topic->followers_count += 1;
            $topic->save();

            return (new Response())->setStatusCode(Response::HTTP_NO_CONTENT /* 204 */);
        });
    }

    /**
     * Unfollow a topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\FeedTopic $model
     * @param int $topicID
     * @return \Illuminate\Http\Response
     */
    public function unfollow(Request $request, FeedTopicModel $model, int $topicID): Response
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

        // If not followed, return 204 response.
        if (! $topic->users()->newPivotStatementForId($user->id)->first()) {
            return $response;
        }

        return $user->getConnection()->transaction(function () use ($topic, $response, $user): Response {
            $topic->users()->detach($user);
            if ($topic->followers_count > 0) {
                $topic->decrement('followers_count', 1);
            }

            return $response;
        });
    }
}
