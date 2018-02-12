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
use Zhiyi\Plus\Concerns\FindMarkdownFileTrait;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use SlimKit\PlusQuestion\API2\Requests\UpdateQuestion as UpdateQuestionRequest;
use SlimKit\PlusQuestion\API2\Requests\PublishQuestion as PublishQuestionRequest;

class QuestionController extends Controller
{
    use FindMarkdownFileTrait;

    /**
     * List all questions.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Question $questionModel
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, QuestionModel $questionModel)
    {
        $userID = $request->user('api')->id ?? 0;
        $limit = max(1, min(30, $request->query('limit', 15)));
        $offset = max(0, $request->query('offset', 0));
        $subject = $request->query('subject');
        $map = [
            'all' => function ($query) {
                $query->orderBy('id', 'desc');
            },
            'new' => function ($query) {
                $query->where('answers_count', 0)
                    ->orderBy('id', 'desc');
            },
            'hot' => function ($query) use ($questionModel) {
                $query->whereBetween('created_at', [
                    $questionModel->freshTimestamp()->subMonth(1),
                    $questionModel->freshTimestamp(),
                ])->where('answers_count', '!=', 0);
                $query->orderBy('answers_count', 'desc');
            },
            'reward' => function ($query) {
                $query->where('amount', '!=', 0)
                    ->orderBy('id', 'desc');
            },
            'excellent' => function ($query) {
                $query->where('excellent', '!=', 0)
                    ->orderBy('id', 'desc');
            },
            'follow' => function ($query) use ($userID) {
                $query->whereExists(function ($query) use ($userID) {
                    $query->from('question_watcher')
                        ->where('question_watcher.user_id', '=', $userID)
                        ->whereRaw('question_watcher.question_id = questions.id');
                });
            },
        ];
        $type = in_array($type = $request->query('type', 'new'), array_keys($map)) ? $type : 'new';
        call_user_func($map[$type], $query = $questionModel
            ->when($subject, function ($query) use ($subject) {
                return $query->where('subject', 'like', '%'.$subject.'%');
            })
            ->limit($limit)
            ->offset($offset)
        );
        $questions = $query->get();
        $questions->load('user');

        return $response->json($questions->map(function (QuestionModel $question) use ($userID) {
            if ($question->anonymity && $question->user_id !== $userID) {
                $question->addHidden('user');
                $question->user_id = 0;
            }

            $question->answer = $question->answers()
                ->with('user')
                ->orderBy('id', 'desc')
                ->first();

            if ($question->answer) {
                if ($question->answer->anonymity && $question->answer->user_id !== $userID) {
                    $question->answer->addHidden('user');
                    $question->answer->user_id = 0;
                }
                $question->answer->liked = (bool) $question->answer->liked($userID);
                $question->answer->collected = (bool) $question->answer->collected($userID);
                $question->answer->rewarded = (bool) $question->answer->rewarders()->where('user_id', $userID)->first();
                $question->look && $question->answer->could = true;

                if ($question->look && $question->answer->invited && (! $question->answer->onlookers()->where('user_id', $userID)->first()) && $question->answer->user_id !== $userID && $question->user_id !== $userID) {
                    $question->answer->could = false;
                    $question->answer->body = null;
                }
            }

            return $question;
        }))->setStatusCode(200);
    }

    /**
     * Get a single question.
     *
     * @param Request $request
     * @param ResponseFactoryContract $response
     * @param QuestionModel $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseFactoryContract $response, QuestionModel $question)
    {
        $userID = $request->user('api')->id ?? 0;
        $loadMap = [
            'topics', 'invitations',
            'answers' => function ($query) {
                $query->where('invited', '!=', 0);
                $query->orderBy('id', 'desc');
            },
            'answers.user',
            'answers.onlookers',
        ];
        $answerResolveCall = function (AnswerModel $answer) use ($userID, $question) {
            $question->look && $answer->could = true;

            $answer->onlookers_count = $answer->onlookers->count();
            $answer->onlookers_total = $answer->onlookers()->withPivot('amount')->sum('amount');

            if ($question->look && (! in_array($userID, $answer->onlookers->pluck('id')->toArray())) && $answer->user_id !== $userID && $question->user_id !== $userID) {
                $answer->could = false;
                $answer->body = null;
                $answer->text_body = null;
            }
            $answer->addHidden('onlookers');

            if ($answer->anonymity && $answer->user_id !== $userID) {
                $answer->addHidden('user');
                $answer->user_id = 0;
            }

            if ($userID) {
                $answer->liked = (bool) $answer->liked($userID);
                $answer->collected = (bool) $answer->collected($userID);
                $answer->rewarded = (bool) $answer->rewarders()->where('user_id', $userID)->first();
            }

            return $answer;
        };

        if (! $question->anonymity || $userID === $question->user_id) {
            $loadMap[] = 'user';
        } elseif ($question->anonymity) {
            $question->user_id = 0;
        }

        if ($userID) {
            $loadMap['watchers'] = function ($query) use ($userID) {
                $query->where('id', $userID);
            };
        }

        $question->load($loadMap);
        $question->addHidden(['answers', 'watchers']);
        $question->watched = ! $userID ? false : (bool) $question->watchers()->newPivotStatementForId($userID)->first();
        $question->my_answer = $question->answers()->with('user')->where('user_id', $userID)->first();
        $question->invitation_answers = $question->answers->map($answerResolveCall);
        $question->adoption_answers = $question->answers()
            ->with('user')
            ->where('adoption', '!=', 0)
            ->where('invited', 0)
            ->get()
            ->map(function (AnswerModel $answer) use ($userID) {
                if ($answer->anonymity && $answer->user_id !== $userID) {
                    $answer->addHidden('user');
                    $answer->user_id = 0;
                }

                if ($userID) {
                    $answer->liked = (bool) $answer->liked($userID);
                    $answer->collected = (bool) $answer->collected($userID);
                    $answer->rewarded = (bool) $answer->rewarders()->where('user_id', $userID)->first();
                }

                return $answer;
            });

        // The question views count +1.
        $question->increment('views_count', 1);

        return $response->json($question, 200);
    }

    /**
     * Publish a question.
     *
     * @param \SlimKit\PlusQuestion\API2\Requests\PublishQuestion $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @param \SlimKit\PlusQuestion\Models\Topic $topicModel
     * @param \SlimKit\PlusQuestion\Models\User $userModel
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(PublishQuestionRequest $request,
                          ResponseFactoryContract $response,
                          QuestionModel $question,
                          TopicModel $topicModel,
                          UserModel $userModel,
                          WalletChargeModel $charge)
    {
        $user = $request->user();

        // Get question base data.
        $subject = $request->input('subject');
        $body = $request->input('body');
        $text_body = $request->input('text_body');
        $anonymity = $request->input('anonymity') ? 1 : 0;
        $amount = intval($request->input('amount')) ?: 0;
        $look = $request->input('look') ? 1 : 0;
        $automaticity = $request->input('automaticity') ? 1 : 0;
        $topicsIDs = array_pluck((array) $request->input('topics', []), 'id');
        $usersIDs = array_pluck((array) $request->input('invitations', []), 'user');

        $images = $this->findMarkdownImageNotWithModels($body ?: '');

        if ($automaticity && ! $amount) {
            return $response->json(['amount' => [trans('plus-question::questions.回答自动入账必须设置悬赏总额')]], 422);
        } elseif ($automaticity && count($usersIDs) !== 1) {
            return $response->json(['invitations' => [trans('plus-question::questions.回答自动入账只能邀请一人')]], 422);
        } elseif ($look && ! $automaticity) {
            return $response->json(['automaticity' => [trans('plus-question::questions.开启围观必须设置自动入账')]], 422);
        } elseif ($look && ! $amount) {
            return $response->json(['amount' => [trans('plus-question::question.开启围观必须设置悬赏金额')]], 422);
        }

        // Find topics.
        $topics = empty($topicsIDs) ? collect() : $topicModel->whereIn('id', $topicsIDs)->get();

        // Find users.
        $users = empty($usersIDs) ? collect() : $userModel->whereIn('id', $usersIDs)->get();

        $question->subject = $subject;
        $question->body = $body;
        $question->text_body = $text_body;
        $question->anonymity = $anonymity;
        $question->amount = $amount;
        $question->automaticity = $automaticity;
        $question->look = $look;
        $question->watchers_count = 1;  // 默认自己关注

        // Charge
        $charge->user_id = $user->id;
        $charge->channel = 'system';
        $charge->action = 0;
        $charge->amount = $amount;
        $charge->subject = trans('plus-question::questions.发布悬赏问答');
        $charge->body = trans('plus-question::questions.发布悬赏问答《%s》', ['subject' => $question->subject]);
        $charge->status = 1;

        try {

            // Save question.
            $user->questions()->save($question);

            // Save relation.
            $user->getConnection()->transaction(function () use (
                $question, $user, $topics, $users,
                $topicModel, $topicsIDs,
                $charge, $images
            ) {

                // Sync topics.
                $question->topics()->sync($topics);

                // Watch question.
                $user->watchingQuestions()->attach($question);

                // Topics questions_count +1
                $topicModel->whereIn('id', $topicsIDs)->increment('questions_count', 1);

                // User questions_count +1
                $user->extra()->firstOrCreate([])->increment('questions_count', 1);

                // Sync invitations
                if (! empty($users)) {
                    $question->invitations()->sync($users);
                }

                // Save charage
                if ($charge->amount) {
                    $user->walletCharges()->save($charge);
                    $user->wallet()->decrement('balance', $charge->amount);
                }

                // Update images.
                $images->each(function ($image) use ($question) {
                    $image->channel = 'question:images';
                    $image->raw = $question->id;
                    $image->save();
                });
            });
        } catch (\Exception $exception) {

            // Delete Question.
            $question->delete();

            throw $exception;
        }

        // 给用户发送邀请通知.
        $users->each(function (UserModel $item) use ($user, $question) {
            $item->sendNotifyMessage(
                'question',
                trans('plus-question::questions.invitation', [
                    'user' => $user->name,
                    'question' => $question->subject,
                ]),
                [
                    'user' => $user,
                    'question' => $question,
                ]
            );
        });

        return $response->json([
            'message' => [trans('plus-question::messages.success')],
            'question' => $question,
        ], 201);
    }

    /**
     * Update a question.
     *
     * @param \SlimKit\PlusQuestion\API2\Requests\UpdateQuestion $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(UpdateQuestionRequest $request,
                           ResponseFactoryContract $response,
                           QuestionModel $question)
    {
        $user = $request->user();

        $anonymity = $request->input('anonymity', $question->anonymity) ? 1 : 0;
        foreach (array_filter($request->only(['subject', 'body', 'text_body'])) as $key => $value) {
            $question->$key = $value;
        }
        $images = $this->findMarkdownImageNotWithModels($question->body ?: '');
        $topicsIDs = array_pluck((array) $request->input('topics', []), 'id');

        $question->anonymity = $anonymity;

        $amount = $request->input('amount');
        if ($amount) {
            if ($question->user_id !== $user->id) {
                return $response->json(['message' => [trans('plus-question::questions.not-owner')]], 403);
            }
            if (($question->amount !== 0 && $question->invitations()->first()) || $question->answers()->where('adoption', 1)->first()) {
                return $response->json(['message' => [trans('plus-question::questions.该问题无法设置悬赏')]], 403);
            }
        }

        $question->getConnection()->transaction(function () use ($question, $images, $topicsIDs, $user, $amount) {

            // 设置悬赏金额并扣款
            if ($amount) {
                $question->amount = $amount;
                $user->wallet()->decrement('balance', $question->amount);
            }

            $question->save();

            if ($topicsIDs) {
                // Count topic`s questions.
                $topicModel = new TopicModel();
                $topicModel->whereIn('id', $question->topics->pluck('id'))->decrement('questions_count', 1);

                $question->topics()->sync($topicsIDs);
                $topicModel->whereIn('id', $topicsIDs)->increment('questions_count', 1);
            }

            // Update images.
            $images->each(function ($image) use ($question) {
                $image->channel = 'question:images';
                $image->raw = $question->id;
                $image->save();
            });
        });

        return $response->make(null, 204);
    }

    /**
     * Reset amount for a question.
     *
     * @author bs<414606094@qq.com>
     * @param \SlimKit\PlusQuestion\API2\Requests\UpdateQuestion $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @return mixed
     */
    public function resetAmount(Request $request,
                                ResponseFactoryContract $response,
                                QuestionModel $question,
                                WalletChargeModel $charge)
    {
        $user = $request->user();
        if ($question->user_id !== $user->id) {
            return $response->json(['message' => [trans('plus-question::questions.not-owner')]], 403);
        }
        if (($question->amount !== 0 && $question->invitations()->first()) || $question->answers()->where('adoption', 1)->first()) {
            return $response->json(['message' => [trans('plus-question::questions.该问题无法设置悬赏')]], 403);
        }

        $question->amount = $request->input('amount', 0);

        // Charge
        $charge->user_id = $user->id;
        $charge->channel = 'system';
        $charge->action = 0;
        $charge->amount = $question->amount;
        $charge->subject = trans('plus-question::questions.发布悬赏问答');
        $charge->body = trans('plus-question::questions.发布悬赏问答《%s》', ['subject' => $question->subject]);
        $charge->status = 1;

        $question->getConnection()->transaction(function () use ($question, $charge, $user) {
            $charge->save();
            $user->wallet()->decrement('balance', $question->amount);
            $question->save();
        });

        return $response->make(null, 204);
    }

    /**
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param  \SlimKit\PlusQuestion\Models\Question $question
     * @return mixed
     */
    public function destory(Request $request,
                            ResponseFactoryContract $response,
                            QuestionModel $question)
    {
        $user = $request->user();
        if ($question->user_id !== $user->id) {
            return $response->json(['message' => [trans('plus-question::questions.not-owner')]], 403);
        }

        $user->getConnection()->transaction(function () use ($user, $question) {
            // 如果有采纳或是自动入账已完成 则不退款
            if (($question->automaticity && ! $question->answers()->where('invited', 1)->first())
            || ($question->amount > 0 && $question->status != 1 && ! $question->answers()->where('adoption', 1)->first())) {
                $user->wallet()->increment('balance', $question->amount);

                $charge = new WalletChargeModel();
                $charge->user_id = $question->user_id;
                $charge->channel = 'system';
                $charge->action = 1;
                $charge->amount = $question->amount;
                $charge->subject = trans('plus-question::questions.refund');
                $charge->body = $charge->subject;
                $charge->status = 1;

                $charge->save();
            }

            if ($applylog = $question->applications()->where('status', '!=', 2)->first()) {
                $user->wallet()->increment('balance', $applylog->amount);

                $charge = new WalletChargeModel();
                $charge->user_id = $question->user_id;
                $charge->channel = 'system';
                $charge->action = 1;
                $charge->amount = $applylog->amount;
                $charge->subject = trans('plus-question::questions.application.退还问题申精费用');
                $charge->body = trans('plus-question::questions.application.退还问题《:subject》的申精费用', ['subject' => $question->subject]);
                $charge->status = 1;

                $charge->save();
            }

            $question->topics()->decrement('questions_count', 1);
            $question->delete();
        });

        return $response->json(null, 204);
    }
}
