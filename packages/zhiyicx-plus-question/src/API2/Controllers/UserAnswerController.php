<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class UserAnswerController extends Controller
{
    /**
     * Get the user's answer.
     *
     * @author bs<414606094@qq.com>
     * @param Request $request
     * @param ApplicationContract $app
     * @return mixed
     */
    public function index(Request $request, ApplicationContract $app)
    {
        $type = $request->query('type', 'all');
        if (! in_array($type, ['all', 'adoption', 'invitation', 'other'])) {
            $type = 'all';
        }

        return response()->json($app->call([$this, $type]), 200);
    }

    /**
     * Get all the user`s answer.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answerModel
     * @return mixed
     */
    public function all(Request $request, AnswerModel $answerModel)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);

        $answers = $answerModel
            ->where('user_id', $user->id)
            ->with('user')
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->whereExists(function ($query) {
                return $query->from('questions')->whereRaw('questions.id = answers.question_id')->where('deleted_at', null);
            })
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()->map(function ($answer) use ($user) {
                $answer->liked = (bool) $answer->liked($user->id);
                $answer->collected = (bool) $answer->collected($user->id);
                $answer->rewarded = (bool) $answer->rewarders()->where('user_id', $user->id)->first();

                return $answer;
            });

        return $answers;
    }

    /**
     * Get the accepted answer of user.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answerModel
     * @return mixed
     */
    public function adoption(Request $request, AnswerModel $answerModel)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);

        $answers = $answerModel
            ->where('user_id', $user->id)
            ->where('adoption', 1)
            ->with('user')
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->whereExists(function ($query) {
                return $query->from('questions')->whereRaw('questions.id = answers.question_id')->where('deleted_at', null);
            })
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()->map(function ($answer) use ($user) {
                $answer->liked = (bool) $answer->liked($user->id);
                $answer->collected = (bool) $answer->collected($user->id);
                $answer->rewarded = (bool) $answer->rewarders()->where('user_id', $user->id)->first();

                return $answer;
            });

        return $answers;
    }

    /**
     * Get the invited answer of user.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answerModel
     * @return mixed
     */
    public function invitation(Request $request, AnswerModel $answerModel)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);

        $answers = $answerModel
            ->where('user_id', $user->id)
            ->where('invited', 1)
            ->with('user')
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->whereExists(function ($query) {
                return $query->from('questions')->whereRaw('questions.id = answers.question_id')->where('deleted_at', null);
            })
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()->map(function ($answer) use ($user) {
                $answer->liked = (bool) $answer->liked($user->id);
                $answer->collected = (bool) $answer->collected($user->id);
                $answer->rewarded = (bool) $answer->rewarders()->where('user_id', $user->id)->first();

                return $answer;
            });

        return $answers;
    }

    /**
     * Get other answer of the user.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answerModel
     * @return mixed
     */
    public function other(Request $request, AnswerModel $answerModel)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);

        $answers = $answerModel
            ->where('user_id', $user->id)
            ->where('invited', 0)
            ->where('adoption', 0)
            ->with('user')
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->whereExists(function ($query) {
                return $query->from('questions')->whereRaw('questions.id = answers.question_id')->where('deleted_at', null);
            })
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()->map(function ($answer) use ($user) {
                $answer->liked = (bool) $answer->liked($user->id);
                $answer->collected = (bool) $answer->collected($user->id);
                $answer->rewarded = (bool) $answer->rewarders()->where('user_id', $user->id)->first();

                return $answer;
            });

        return $answers;
    }
}
