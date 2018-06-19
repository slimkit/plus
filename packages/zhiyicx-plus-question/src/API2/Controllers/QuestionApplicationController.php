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
use Zhiyi\Plus\Models\WalletCharge;
use SlimKit\PlusQuestion\Models\Question;
use SlimKit\PlusQuestion\Models\QuestionApplication;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class QuestionApplicationController extends Controller
{
    // Application amount in background settings
    protected $apply_amount;

    public function __construct()
    {
        $this->apply_amount = config('question.apply_amount');
    }

    /**
     * Add an application for a question.
     *
     * @author bs<414606094@qq.com>
     * @param Request             $request
     * @param Question            $question
     * @param WalletCharge        $charge
     * @param QuestionApplication $application
     * @return mixed
     */
    public function store(Request $request, Question $question, WalletCharge $charge, QuestionApplication $application)
    {
        $user = $request->user();
        if ($user->id !== $question->user_id) {
            return response()->json(['message' => [trans('plus-question::questions.not-owner')]], 403);
        }

        if ($question->applications()->where('expires_at', null)->first()) {
            return response()->json(['message' => [trans('plus-question::questions.application.already-exists')]], 422);
        }

        if ($user->wallet->balance < $this->apply_amount) {
            return response()->json(['message' => [trans('plus-question::questions.Insufficient balance')]], 422);
        }

        $user->getConnection()->transaction(function () use ($user, $question, $charge, $application) {
            // 申请扣费
            $user->wallet()->decrement('balance', $this->apply_amount);

            // 扣费账单
            $charge->user_id = $user->id;
            $charge->channel = 'system';
            $charge->action = 0;
            $charge->amount = $this->apply_amount;
            $charge->subject = trans('plus-question::questions.application.支付问题申精积分');
            $charge->body = trans('plus-question::questions.application.支付问题《:subject》的申精积分', ['subject' => $question->subject]);
            $charge->status = 1;
            $charge->save();

            // 保存申请记录
            $application->user_id = $user->id;
            $application->amount = $this->apply_amount;
            $application->status = 0;
            $question->applications()->save($application);
        });

        return response()->json([
            'message' => [trans('plus-question::messages.success')],
        ], 201);
    }

    /**
     * 新版消耗积分申请精选接口.
     *
     * @author bs<414606094@qq.com>
     * @param Request             $request
     * @param Question            $question
     * @param QuestionApplication $application
     * @return mixed
     */
    public function newStore(Request $request, Question $question, QuestionApplication $application)
    {
        $user = $request->user();
        if ($user->id !== $question->user_id) {
            return response()->json(['message' => [trans('plus-question::questions.not-owner')]], 403);
        }

        if ($question->applications()->where('expires_at', null)->first()) {
            return response()->json(['message' => [trans('plus-question::questions.application.already-exists')]], 422);
        }

        if ($user->currency->sum < $this->apply_amount) {
            return response()->json(['message' => [trans('plus-question::questions.Insufficient balance')]], 422);
        }

        $user->getConnection()->transaction(function () use ($user, $question, $application) {
            $process = new UserProcess();
            $process->prepayment($user->id, $this->apply_amount, 0, trans('plus-question::questions.application.支付问题申精积分'), trans('plus-question::questions.application.支付问题《:subject》的申精积分', ['subject' => $question->subject]));

            // 保存申请记录
            $application->user_id = $user->id;
            $application->amount = $this->apply_amount;
            $application->status = 0;
            $question->applications()->save($application);
        });

        return response()->json([
            'message' => [trans('plus-question::messages.success')],
        ], 201);
    }
}
