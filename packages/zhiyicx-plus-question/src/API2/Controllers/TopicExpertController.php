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
use Zhiyi\Plus\Models\User as UserModel;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TopicExpertController extends Controller
{
    /**
     * Get all experts for the topics.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Topic $topic
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, TopicModel $topic)
    {
        $userID = $request->user('api')->id ?? 0;
        $after = $request->query('after');
        $users = $topic->experts()
            ->with('tags')
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->limit(20)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return $response->json($users->map(function (UserModel $user) use ($userID) {
            $user->following = $user->hasFollwing($userID);
            $user->follower = $user->hasFollower($userID);

            return $user;
        }))->setStatusCode(200);
    }

    /**
     * Get all experts for multiple topics.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Topic $topicModel
     * @return mixed
     * @author bs <414606094@qq.com>
     */
    public function list(Request $request, ResponseFactoryContract $response, UserModel $userModel)
    {
        $userID = $request->user('api')->id ?? 0;
        $topics = array_filter(explode(',', $request->query('topics')));
        $offset = $request->query('offset', 0);
        $keyword = $request->query('keyword');
        $limit = $request->query('limit', 15);

        $users = $userModel->select('users.*')->with('tags')
            ->join('topic_expert', function ($join) use ($topics) {
                return $join->on('users.id', '=', 'topic_expert.user_id')->whereIn('topic_id', $topics);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('name', 'LIKE', '%'.$keyword.'%');
            })
            ->distinct()
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $response->json($users->map(function ($user) use ($userID) {
            $user->following = $user->hasFollwing($userID);
            $user->follower = $user->hasFollower($userID);

            return $user;
        }))->setStatusCode(200);
    }
}
