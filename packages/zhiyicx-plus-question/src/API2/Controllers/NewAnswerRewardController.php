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
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use SlimKit\PlusQuestion\Models\TopicExpertIncome as ExpertIncomeModel;
use SlimKit\PlusQuestion\API2\Requests\NewAnswerReward as NewAnswerRewardRequest;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class NewAnswerRewardController extends Controller
{
    /**
     * 元到分转换比列.
     */
    const RATIO = 100;

    public function store(NewAnswerRewardRequest $request, ResponseFactoryContract $response, UserProcess $process, AnswerModel $answer, GoldType $goldModel)
    {
        $goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '积分';
        $amount = (int) $request->input('amount');
        $user = $request->user();
        $target = $answer->user;

        if (! $target) {
            return $response->json(['message' => [trans('plus-question::answers.reward.not-user')]], 422);
        }

        if ($target->id === $user->id) {
            return $response->json(['message' => '不能打赏自己'], 422);
        }

        if (! $user->currency || $user->currency->sum < $amount) {
            return response()->json(['message' => '余额不足'], 403);
        }

        return $response->json($answer->getConnection()->transaction(function () use ($answer, $user, $target, $amount, $process, $goldName) {
            $process->prepayment($user->id, $amount, $target->id, sprintf('打赏“%s”的回答', $target->name), sprintf('打赏“%s”的回答，%s扣除%s', $target->name, $goldName, $amount));
            $process->receivables($target->id, $amount, $user->id, sprintf('“%s”打赏了你的回答', $user->name), sprintf('“%s”打赏了你的回答，%s增加%s', $user->name, $goldName, $amount));
            // $money = $amount / self::RATIO;

            // $targetOrder = $manager->driver(Order::TARGET_TYPE_REWARD)->reward([
            //     'reward_resource' => $answer,
            //     'order' => [
            //         'user' => $user,
            //         'target' => $target,
            //         'amount' => $amount,
            //         'user_order_body' => sprintf('打赏问答回答，钱包扣除%s元', $money),
            //         'target_order_body' => sprintf('问答回答被打赏，钱包增加%s元', $money),
            //     ],
            //     'notice' => [
            //         'type' => 'question:answer-reward',
            //         'detail' => ['user' => $user, 'answer' => $answer],
            //         'message' => sprintf('你问答回答《%s》，被%s打赏%s元', $answer->body, $user->name, $money),
            //     ],
            // ], 'manual');
            $target->sendNotifyMessage('question:answer-reward', sprintf('“%s”打赏了你的回答', $user->name), [
                'answer' => $answer,
                'user' => $user,
            ]);
            // 1.8启用, 新版未读消息提醒
            $userCount = UserCountModel::firstOrNew([
                'type' => 'user-system',
                'user_id' => $target->id
            ]);
            $userCount->total += 1;
            $userCount->save();

            // inrement rewarder_count
            $answer->increment('rewarder_count');
            // inrement rewards_amount
            $answer->increment('rewards_amount', $amount);
            // check if the user is a expert, record income.
            $answer->question->load('topics.experts');
            // get all expert of all the topics belongs to the question.
            $allexpert = $answer->question->topics->map(function ($topic) {
                return $topic->experts->map(function ($expert) {
                    return $expert->id;
                });
            })->flatten()->toArray();

            if (in_array($target->id, $allexpert)) {
                $income = new ExpertIncomeModel();
                // $income->charge_id = $targetOrder->id;
                $income->user_id = $target->id;
                $income->amount = $amount;
                $income->type = 'reward';

                $income->save();
            }

            return ['message' => trans('plus-question::messages.success')];
        }));
    }
}
