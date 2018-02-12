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

namespace SlimKit\PlusQuestion\Admin\Controllers;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Reward as RewardModel;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use SlimKit\PlusQuestion\Admin\Requests\StoreTopic as TopicRequest;
use SlimKit\PlusQuestion\Admin\Requests\StoreTopicAvatar as TopicAvatarRequest;

class TopicController extends Controller
{
    /**
     * list of topics.
     *
     * @param Request $request
     * @param TopicModel $topicModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, TopicModel $topicModel)
    {
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);
        $name = $request->query('name');
        $query = $topicModel->when($name, function ($query) use ($name) {
            return $query->where('name', 'like', '%'.$name.'%');
        });

        $total = $query->count('id');
        $topics = $query->limit($limit)->offset($offset)->orderBy('sort', 'desc')->orderBy('id', 'desc')->get();
        $topics->makeVisible(['created_at', 'updated_at']);

        return response()->json($topics, 200, ['x-total' => $total]);
    }

    /**
     * 创建话题.
     *
     * @param TopicRequest $request
     * @param TopicModel $topicModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(TopicRequest $request, TopicModel $topicModel)
    {
        if ($topicModel->newQuery()->where('name', $request->input('name'))->first()) {
            return response()->json(['message' => ['话题名称已存在']], 422);
        }

        $topicModel->name = $request->input('name');
        $topicModel->description = $request->input('description');
        $topicModel->sort = $request->input('sort', 0);

        $topicModel->save();

        return response()->json($topicModel, 201);
    }

    /**
     * 更新话题封面.
     *
     * @param TopicAvatarRequest $request
     * @param TopicModel $topic
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function storeAvatar(TopicAvatarRequest $request, TopicModel $topic)
    {
        $avatar = $request->file('avatar');
        if (! $avatar->isValid()) {
            return $response->json(['messages' => [$avatar->getErrorMessage()]], 400);
        }

        return $topic->storeAvatar($avatar)
            ? response()->json('', 204)
            : response()->json(['message' => ['上传失败']], 500);
    }

    /**
     * add experts for a topic.
     *
     * @param Request $request
     * @param TopicModel $topic
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function storeExperts(Request $request, TopicModel $topic, User $expert)
    {
        if ($topic->experts()->where('user_id', $expert->id)->count() > 0) {
            return response()->json(['message' => ['该用户已设置为该话题下专家，请勿重复操作']], 422);
        }
        $sort = $request->input('sort', 0);
        $topic->getConnection()->transaction(function () use ($expert, $topic, $sort) {
            $topic->experts()->attach($expert, ['sort' => $sort]);
            $topic->increment('experts_count', 1);
        });

        return response()->json('', 201);
    }

    /**
     * update topic.
     *
     * @param Request $request
     * @param TopicModel $topic
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function update(Request $request, TopicModel $topic)
    {
        if ($request->input('name') != $topic->name && $topic->newQuery()->where('name', $request->input('name'))->first()) {
            return response()->json(['message' => ['话题名称已存在']], 422);
        }

        $request->has('name') && $topic->name = $request->input('name');
        $request->has('description') && $topic->description = $request->input('description');
        $request->has('sort') && $topic->sort = $request->input('sort');

        $topic->save();

        return response()->json('', 201);
    }

    /**
     * 删除话题.
     *
     * @param TopicModel $topic
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function delete(TopicModel $topic)
    {
        $topic->followers()->detach();
        $topic->experts()->detach();
        $topic->delete();

        return response()->json('', 204);
    }

    /**
     * single topic info.
     *
     * @param TopicModel $topic
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function show(TopicModel $topic)
    {
        return response()->json($topic, 200);
    }

    /**
     * list of topic followers.
     *
     * @param Request $request
     * @param TopicModel $topic
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function followers(Request $request, TopicModel $topic, AnswerModel $answer, QuestionModel $question, RewardModel $reward)
    {
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);
        $user = $request->query('name');

        $query = $topic->followers()->when($user, function ($query) use ($user) {
            return $query->where('name', 'like', sprintf('%%%s%%', $user));
        })
            ->selectRaw('*,topic_user.created_at as follow_time');

        $total = $query->count();

        return response()->json($query->limit($limit)->offset($offset)->get()->map(function ($follower) use ($answer, $question, $reward) {
            // 被邀请的收入
            $follower->invited_income = $question->where('automaticity', 1)->where('amount', '!=', 0)->join('answers', function ($join) use ($follower) {
                return $join->on('questions.id', '=', 'answers.question_id')->where('answers.user_id', $follower->id)->where('answers.invited', 1);
            })->sum('questions.amount');

            // 公开悬赏被采纳收入
            $follower->adoption_income = $question->where('automaticity', 0)->where('amount', '!=', 0)->join('answers', function ($join) use ($follower) {
                return $join->on('questions.id', '=', 'answers.question_id')->where('answers.user_id', $follower->id)->where('answers.adoption', 1);
            })->sum('questions.amount');

            // 获得的问答打赏收入
            $follower->reward_income = $reward->where('target_user', $follower->id)->where('rewardable_type', 'question-answers')->sum('amount');

            // 收到的问题围观收入
            $follower->onlooker_income = $answer->where('invited', 1)->join('questions', function ($join) use ($follower) {
                return $join->on('answers.question_id', '=', 'questions.id')->where('questions.user_id', $follower->id)->where('questions.look', 1);
            })->get()->sum(function ($answer) {
                return $answer->onlookers()->withPivot('amount')->sum('amount');
            });

            return $follower;
        }), 200, ['x-total' => $total]);
    }

    /**
     * list of topic experts.
     *
     * @param Request $request
     * @param TopicModel $topic
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function experts(Request $request, TopicModel $topic, AnswerModel $answer, QuestionModel $question, RewardModel $reward)
    {
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);

        $total = $topic->experts()->count();
        $topic->load(['experts' => function ($query) use ($limit, $offset) {
            return $query->limit($limit)->offset($offset)->orderBy('sort', 'desc')->selectRaw('*, topic_expert.created_at as expert_time, topic_expert.sort as expert_sort');
        }]);

        return response()->json($topic->experts->map(function ($expert) use ($answer, $question, $reward) {
            // 被邀请的收入
            $expert->invited_income = $question->where('automaticity', 1)->where('amount', '!=', 0)->join('answers', function ($join) use ($expert) {
                return $join->on('questions.id', '=', 'answers.question_id')->where('answers.user_id', $expert->id)->where('answers.invited', 1);
            })->sum('questions.amount');

            // 公开悬赏被采纳收入
            $expert->adoption_income = $question->where('automaticity', 0)->where('amount', '!=', 0)->join('answers', function ($join) use ($expert) {
                return $join->on('questions.id', '=', 'answers.question_id')->where('answers.user_id', $expert->id)->where('answers.adoption', 1);
            })->sum('questions.amount');

            // 获得的问答打赏收入
            $expert->reward_income = $reward->where('target_user', $expert->id)->where('rewardable_type', 'question-answers')->sum('amount');

            // 收到的问题围观收入
            $expert->onlooker_income = $answer->where('invited', 1)->join('questions', function ($join) use ($expert) {
                return $join->on('answers.question_id', '=', 'questions.id')->where('questions.user_id', $expert->id)->where('questions.look', 1);
            })->get()->sum(function ($answer) {
                return $answer->onlookers()->withPivot('amount')->sum('amount');
            });

            return $expert;
        }), 200, ['x-total' => $total]);
    }

    /**
     * remove follower of topic.
     *
     * @param Request $request
     * @param TopicModel $topic
     * @param User $user
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function removeFollowers(Request $request, TopicModel $topic, User $user)
    {
        $topic->followers()->detach($user);
        $topic->decrement('follows_count', 1);

        return response()->json('', 204);
    }

    /**
     * remove expert of topic.
     *
     * @param Request $request
     * @param TopicModel $topic
     * @param User $user
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function removeExperts(Request $request, TopicModel $topic, User $user)
    {
        $topic->experts()->detach($user);
        $topic->decrement('experts_count', 1);

        return response()->json('', 204);
    }

    /**
     * 话题专家排序.
     *
     * @param  Request    $request
     * @param  TopicModel $topic
     * @param  User       $user
     * @return mixed
     */
    public function sortExperts(Request $request, TopicModel $topic, User $user)
    {
        $sort = (int) $request->input('sort');

        $topic->experts()->updateExistingPivot($user->id, ['sort' => $sort]);

        return response()->json('', 204);
    }

    /**
     * 话题排序.
     *
     * @param  Request    $request
     * @param  TopicModel $topic
     * @return mixed
     */
    public function sort(Request $request, TopicModel $topic)
    {
        $sort = (int) $request->input('sort');

        $topic->sort = $sort;
        $topic->save();

        return response()->json('', 204);
    }

    /**
     * 话题开启.
     *
     * @param  Request    $request
     * @param  TopicModel $topic
     * @return mixed
     */
    public function status(Request $request, TopicModel $topic)
    {
        $topic->status = ! $topic->status;
        $topic->save();

        return response()->json('', 204);
    }
}
