<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\API2\Controllers\Feed;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;

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
     * @param  Request  $request
     * @param  FeedTopicModel  $model
     * @param  int  $topicID
     *
     * @return Response
     */
    public function follow(
        Request $request,
        FeedTopicModel $model,
        int $topicID
    ): Response {
        // Fetch the request authentication user model.
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

        $link = $topic->users()->wherePivot('user_id', $user->id)->first();
        if ($link) {
            if ($link->pivot->following_at ?? false) {
                return (new Response())->setStatusCode(Response::HTTP_NO_CONTENT /* 204 */);
            }
        }

        $feedsCount = $topic->feeds()->where('user_id', $user->id)->count();

        return $user->getConnection()->transaction(function () use (
            $topic,
            $user,
            $feedsCount
        ): Response {
            return $topic->getConnection()->transaction(function () use (
                $user,
                $topic,
                $feedsCount
            ) {
                $topic->users()->attach($user, [
                    'following_at' => new Carbon(),
                    'feeds_count'  => $feedsCount,
                ]);
                // }
                $topic->increment('followers_count', 1);

                return (new Response)->setStatusCode(Response::HTTP_NO_CONTENT /* 204 */);
            });
        });
    }

    /**
     * unFollow a topic.
     *
     * @param  Request  $request
     * @param  FeedTopicModel  $model
     * @param  int  $topicID
     *
     * @return Response
     */
    public function unfollow(
        Request $request,
        FeedTopicModel $model,
        int $topicID
    ): Response {
        // Fetch the request authentication user model.
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
        $response
            = (new Response)->setStatusCode(Response::HTTP_NO_CONTENT /* 204 */);

        // If not followed, return 204 response.
        $link = $topic->users()->wherePivot('user_id', $user->id)
            ->first()->pivot;
        if (! $link || ! ($link->following_at ?? false)) {
            return $response;
        }

        return $user->getConnection()->transaction(function () use (
            $topic,
            $response,
            $user
        ): Response {
            return $topic->getConnection()->transaction(function () use (
                $topic,
                $user,
                $response
            ) {
                if ($topic->followers_count > 0) {
                    $topic->decrement('followers_count', 1);
                }
                $topic->users()->detach($user);

                return $response;
            });
        });
    }
}
