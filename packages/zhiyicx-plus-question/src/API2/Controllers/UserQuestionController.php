<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class UserQuestionController extends Controller
{
    /**
     * List watched questions for the authenticated user.
     *
     * @param Request $request
     * @param ResponseFactoryContract $response
     * @return moxed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $limit = max(1, min(30, $request->query('limit', 15)));
        $offset = max(0, $request->query('offset', 0));

        $questions = $user->watchingQuestions()
            ->with('user')
            ->orderBy($user->watchingQuestions()->createdAt(), 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $response->json($questions->map(function (QuestionModel $question) use ($user) {
            if ($question->anonymity && $question->user_id !== $user->id) {
                $question->addHidden('user');
                $question->user_id = 0;
            }

            return $question;
        }))->setStatusCode(200);
    }

    /**
     * Watch a question.
     *
     * @param Request $request
     * @param ResponseFactoryContract $response
     * @param QuestionModel $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, ResponseFactoryContract $response, QuestionModel $question)
    {
        $user = $request->user();

        if ($user->watchingQuestions()->newPivotStatementForId($question->id)->first()) {
            return $response->json(['message' => [trans('plus-question::users.questions.watched')]], 422);
        }

        $user->watchingQuestions()->attach($question);
        $question->increment('watchers_count', 1);

        return $response->make('', 204);
    }

    /**
     * Unwatch a question.
     *
     * @param Request $request
     * @param ResponseFactoryContract $response
     * @param QuestionModel $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(Request $request, ResponseFactoryContract $response, QuestionModel $question)
    {
        $user = $request->user();

        if (! $user->watchingQuestions()->newPivotStatementForId($question->id)->first()) {
            return $response->json(['message' => [trans('plus-question::users.questions.not-watching')]], 422);
        }

        $user->watchingQuestions()->detach($question);
        $question->decrement('watchers_count', 1);

        return $response->make('', 204);
    }

    /**
     * Get the user's question.
     *
     * @author bs<414606094@qq.com>
     * @param Request $request
     * @param ResponseFactoryContract $response
     * @param ApplicationContract $app
     * @return mixed
     */
    public function questions(Request $request, ResponseFactoryContract $response, ApplicationContract $app)
    {
        $type = $request->query('type', 'all');
        $user_id = $request->has('user_id') ? (int) $request->query('user_id') : $request->user('api')->id ?? 0;

        if (! in_array($type, ['all', 'invitation', 'reward', 'other'])) {
            $type = 'all';
        }
        return $response->json($app->call([$this, $type], ['user_id' => $user_id]), 200);
    }

    /**
     * Get all.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Question $questionModel
     * @return Collection
     */
    public function all(Request $request, QuestionModel $questionModel, int $user_id)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $questions = $questionModel->with('user')
        ->where('user_id', $user_id)
        ->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })
        ->limit($limit)
        ->orderBy('id', 'desc')
        ->get();

        return $questionModel->getConnection()->transaction(function () use ($questions, $user_id) {
            return $questions->map(function ($question) use ($user_id) {
                $question->answer = $question->answers()
                    ->with('user')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($question->answer) {
                    if ($question->answer->anonymity && $question->answer->user_id !== $user_id) {
                        $question->answer->addHidden('user');
                        $question->answer->user_id = 0;
                    }
                    $question->answer->liked = (bool) $question->answer->liked($user_id);
                    $question->answer->collected = (bool) $question->answer->collected($user_id);
                    $question->answer->rewarded = (bool) $question->answer->rewarders()->where('user_id', $user_id)->first();
                }

                return $question;
            });
        });
    }

    /**
     * Get invitation questions.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Question $questionModel
     * @return Collection
     */
    public function invitation(Request $request, QuestionModel $questionModel, int $user_id)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $questions = $questionModel->with('user')
        ->whereExists(function ($query) {
            return $query->from('question_invitation')->whereRaw('question_invitation.question_id = questions.id');
        })
        ->where('user_id', $user_id)
        ->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })
        ->orderBy('questions.id', 'desc')
        ->limit($limit)
        ->get();

        return $questionModel->getConnection()->transaction(function () use ($questions, $user_id) {
            return $questions->map(function ($question) use ($user_id) {
                $question->answer = $question->answers()
                    ->with('user')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($question->answer) {
                    if ($question->answer->anonymity && $question->answer->user_id !== $user_id) {
                        $question->answer->addHidden('user');
                        $question->answer->user_id = 0;
                    }
                    $question->answer->liked = (bool) $question->answer->liked($user_id);
                    $question->answer->collected = (bool) $question->answer->collected($user_id);
                    $question->answer->rewarded = (bool) $question->answer->rewarders()->where('user_id', $user_id)->first();
                }

                return $question;
            });
        });
    }

    /**
     * Get reward questions.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Question $questionModel
     * @return Collection
     */
    public function reward(Request $request, QuestionModel $questionModel, int $user_id)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $questions = $questionModel->with('user')
        ->where('user_id', $user_id)
        ->where('amount', '>', 0)
        ->whereNotExists(function ($query) {
            return $query->from('question_invitation')->whereRaw('question_invitation.question_id = questions.id');
        })
        ->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })
        ->limit($limit)
        ->orderBy('questions.id', 'desc')
        ->get();

        return $questionModel->getConnection()->transaction(function () use ($questions, $user_id) {
            return $questions->map(function ($question) use ($user_id) {
                $question->answer = $question->answers()
                    ->with('user')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($question->answer) {
                    if ($question->answer->anonymity && $question->answer->user_id !== $user_id) {
                        $question->answer->addHidden('user');
                        $question->answer->user_id = 0;
                    }
                    $question->answer->liked = (bool) $question->answer->liked($user_id);
                    $question->answer->collected = (bool) $question->answer->collected($user_id);
                    $question->answer->rewarded = (bool) $question->answer->rewarders()->where('user_id', $user_id)->first();
                }

                return $question;
            });
        });
    }

    /**
     * Get other questions.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Question $questionModel
     * @return Collection
     */
    public function other(Request $request, QuestionModel $questionModel, int $user_id)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $questions = $questionModel->with('user')
        ->where('user_id', $user_id)
        ->where('amount', '=', 0)
        ->whereNotExists(function ($query) {
            return $query->from('question_invitation')->whereRaw('question_invitation.question_id = questions.id');
        })
        ->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })
        ->limit($limit)
        ->orderBy('questions.id', 'desc')
        ->get();

        return $questionModel->getConnection()->transaction(function () use ($questions, $user_id) {
            return $questions->map(function ($question) use ($user_id) {
                $question->answer = $question->answers()
                    ->with('user')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($question->answer) {
                    if ($question->answer->anonymity && $question->answer->user_id !== $user_id) {
                        $question->answer->addHidden('user');
                        $question->answer->user_id = 0;
                    }
                    $question->answer->liked = (bool) $question->answer->liked($user_id);
                    $question->answer->collected = (bool) $question->answer->collected($user_id);
                    $question->answer->rewarded = (bool) $question->answer->rewarders()->where('user_id', $user_id)->first();
                }

                return $question;
            });
        });
    }
}
