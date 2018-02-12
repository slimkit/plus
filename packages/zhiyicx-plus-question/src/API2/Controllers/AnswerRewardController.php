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
use Zhiyi\Plus\Models\Reward as RewardModel;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use SlimKit\PlusQuestion\Models\TopicExpertIncome as ExpertIncomeModel;
use SlimKit\PlusQuestion\API2\Requests\AnswerReward as AnswerRewardRequest;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class AnswerRewardController extends Controller
{
    /**
     * Get all answer rewarders.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Answer $answer
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, AnswerModel $answer)
    {
        $offset = max(0, $request->query('offset', 0));
        $limit = max(1, min(30, $request->query('limit', 15)));
        $orderMap = [
            'time' => 'id',
            'amount' => 'amount',
        ];
        $orderType = in_array($orderType = $request->query('order_type', 'time'), array_keys($orderMap)) ? $orderType : 'time';

        $rewarders = $answer->rewarders()
            ->with('user')
            ->orderBy($orderMap[$orderType], 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $response->json($rewarders, 200);
    }

    /**
     * Give a reward.
     *
     * @param \SlimKit\PlusQuestion\API2\Requests\AnswerReward $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Answer $answer
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(AnswerRewardRequest $request, ResponseFactoryContract $response, AnswerModel $answer)
    {
        $amount = $request->input('amount');
        $user = $request->user();
        $respondent = $answer->user;

        if (! $respondent) {
            return $response->json(['message' => [trans('plus-question::answers.reward.not-user')]], 422);
        }

        $userCharge = new WalletChargeModel();
        $userCharge->user_id = $user->id;
        $userCharge->channel = 'user';
        $userCharge->account = $respondent->id;
        $userCharge->action = 0;
        $userCharge->amount = $amount;
        $userCharge->subject = trans('plus-question::answers.reward.send-reward');
        $userCharge->body = $userCharge->subject;
        $userCharge->status = 1;

        $respondentCharge = new WalletChargeModel();
        $respondentCharge->user_id = $respondent->id;
        $respondentCharge->channel = 'user';
        $respondentCharge->account = $user->id;
        $respondentCharge->action = 1;
        $respondentCharge->amount = $amount;
        $respondentCharge->subject = trans('plus-question::answers.reward.get-reward');
        $respondentCharge->body = $respondentCharge->subject;
        $respondentCharge->status = 1;

        return $response->json($answer->getConnection()->transaction(function () use ($answer, $user, $respondent, $userCharge, $respondentCharge) {

            // 增减相应钱包
            $user->wallet()->decrement('balance', $userCharge->amount);
            $respondent->wallet()->increment('balance', $userCharge->amount);

            // Save log.
            $reward = new RewardModel();
            $reward->user_id = $user->id;
            $reward->target_user = $respondent->id;
            $reward->amount = $userCharge->amount;
            $answer->rewarders()->save($reward);

            // increment reward for the answer.
            $answer->rewards_amount += $userCharge->amount;
            $answer->rewarder_count += 1;
            $answer->save();

            // save user charge.
            $userCharge->save();
            $respondentCharge->save();

            // check if the user is a expert, record income.
            $answer->question->load('topics.experts');
            // get all expert of all the topics belongs to the question.
            $allexpert = $answer->question->topics->map(function ($topic) {
                return $topic->experts->map(function ($expert) {
                    return $expert->id;
                });
            })->flatten()->toArray();

            // send notify
            $respondent->sendNotifyMessage('question:answer-reward', trans('plus-question::answers.reward.get-reward'), [
                'answer' => $answer,
                'user' => $user,
            ]);

            if (in_array($respondent->id, $allexpert)) {
                $income = new ExpertIncomeModel();
                $income->charge_id = $respondentCharge->id;
                $income->user_id = $respondent->id;
                $income->amount = $respondentCharge->amount;
                $income->type = 'reward';

                $income->save();
            }

            return ['message' => trans('plus-question::messages.success')];
        }))->setStatusCode(201);
    }
}
