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
use Zhiyi\Plus\Models\GoldType;
use Zhiyi\Plus\Models\Reward as RewardModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
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
     * @param \Illuminate\Contracts\Routing\ResponseFactory    $response
     * @param \SlimKit\PlusQuestion\Models\Answer              $answer
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @throws \Throwable
     */
    public function store(AnswerRewardRequest $request, ResponseFactoryContract $response, AnswerModel $answer, GoldType $goldModel, UserProcess $process)
    {
        $goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '积分';
        $amount = $request->input('amount');
        $user = $request->user();
        $respondent = $answer->user;

        if (! $respondent) {
            return $response->json(['message' => trans('plus-question::answers.reward.not-user')], 422);
        }

        if (! $user->currency || $user->currency->sum < $amount) {
            return $response->json(['message' => $goldName.'不足'], 403);
        }

        return $response->json($answer->getConnection()->transaction(function () use ($answer, $user, $respondent, $process, $amount) {
            $answer->reward($user, $amount);
            $process->prepayment($user->id, $amount, $respondent->id, sprintf('打赏“%s”的回答', $respondent->name), sprintf('打赏“%s”的回答，%s扣除%s', $respondent->name, $this->goldName, $amount));
            $process->receivables($respondent->id, $amount, $user->id, sprintf('“%s”打赏了你的帖子', $user->name), sprintf('“%s”打赏了你的回答，%s增加%s', $user->name, $this->goldName, $amount));
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
            // 1.8启用, 新版未读消息提醒
            $userCount = UserCountModel::firstOrNew([
                'type' => 'user-system',
                'user_id' => $respondent->id
            ]);
            $userCount->total += 1;
            $userCount->save();

            if (in_array($respondent->id, $allexpert)) {
                $income = new ExpertIncomeModel();
                $income->charge_id = $respondent->id;
                $income->user_id = $respondent->id;
                $income->amount = $respondent->amount;
                $income->type = 'reward';

                $income->save();
            }

            return ['message' => trans('plus-question::messages.success')];
        }))->setStatusCode(201);
    }
}
