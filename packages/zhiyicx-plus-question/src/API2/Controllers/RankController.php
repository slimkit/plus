<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use DB;
use Carbon\Carbon;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;

class RankController extends Controller
{
    /**
     * 获取解答排行榜.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answerModel
     * @param  Carbon $datetime
     * @return mixed
     */
    public function answers(Request $request, AnswerModel $answerModel, Carbon $datetime)
    {
        $user = $request->user('api')->id ?? 0;
        $type = $request->query('type', 'day');
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        switch ($type) {
            case 'day':
                $date = $datetime->subDay();
                break;
            case 'week':
                $date = $datetime->subWeek();
                break;
            case 'month':
                $date = $datetime->subMonth();
                break;
            default:
                $date = $datetime->subDay();
                break;
        }

        $answers = $answerModel->select('user_id', DB::raw('count(user_id) as count'))
        ->where('created_at', '>', $date)
        ->with(['user' => function ($query) {
            return $query->select('id', 'name', 'sex');
        }])
        ->groupBy('user_id')
        ->orderBy('count', 'desc')
        ->offset($offset)
        ->take($limit)
        ->get();

        return response()->json($answerModel->getConnection()->transaction(function () use ($answers, $user, $offset) {
            return $answers->map(function ($answer, $key) use ($user, $offset) {
                $answer->user->following = $answer->user->hasFollwing($user);
                $answer->user->follower = $answer->user->hasFollower($user);

                $return = $answer->user->toArray();
                $return['extra']['rank'] = $key + $offset + 1;
                $return['extra']['count'] = (int) $answer->count;

                return $return;
            });
        }), 200);
    }

    /**
     * 获取回答点赞数排行榜.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answerModel
     * @return mixed
     */
    public function likes(Request $request, AnswerModel $answerModel)
    {
        $user = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $answers = $answerModel->select('user_id', DB::raw('sum(likes_count) as count'))
        ->with(['user' => function ($query) {
            return $query->select('id', 'name', 'sex');
        }])
        ->groupBy('user_id')
        ->orderBy('count', 'desc')
        ->offset($offset)
        ->take($limit)
        ->get();

        return response()->json($answerModel->getConnection()->transaction(function () use ($answers, $user, $offset) {
            return $answers->map(function ($answer, $key) use ($user, $offset) {
                $answer->user->extra->count = (int) $answer->count; // 回答点赞数
                $answer->user->extra->rank = $key + $offset + 1; // 排名

                $answer->user->following = $answer->user->hasFollwing($user);
                $answer->user->follower = $answer->user->hasFollower($user);

                $return = $answer->user->toArray();
                $return['extra']['rank'] = $key + $offset + 1;
                $return['extra']['count'] = (int) $answer->count;

                return $return;
            });
        }), 200);
    }

    /**
     * Get the rank of expert`s income.
     *
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answerModel
     * @return mixed
     */
    public function expertIncome(Request $request, User $userModel)
    {
        $auth = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $users = $userModel->select('users.id', 'users.name', 'users.sex')
            ->join(DB::raw('(select `user_id`, SUM(`amount`) as `count` from `topic_expert_income` group by `user_id`) as count'), function ($join) {
                return $join->on('users.id', '=', 'count.user_id');
            })
            ->orderBy('count.count', 'desc')
            ->offset($offset)
            ->take($limit)
            ->get();

        return response()->json($userModel->getConnection()->transaction(function () use ($users, $auth, $offset) {
            return $users->map(function ($user, $key) use ($auth, $offset) {
                $user->following = $user->hasFollwing($auth);
                $user->follower = $user->hasFollower($auth);

                $return = $user->toArray();
                $return['extra']['rank'] = $key + $offset + 1;

                return $return;
            });
        }), 200);
    }
}
