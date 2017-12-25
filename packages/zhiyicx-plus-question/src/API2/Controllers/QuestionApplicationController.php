<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\WalletCharge;
use SlimKit\PlusQuestion\Models\Question;
use SlimKit\PlusQuestion\Models\QuestionApplication;

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
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Question $question
     * @param  Zhiyi\Plus\Models\WalletCharge $charge
     * @param  SlimKit\PlusQuestion\Models\QuestionApplication $application
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
            $charge->subject = trans('plus-question::questions.application.支付问题申精费用');
            $charge->body = trans('plus-question::questions.application.支付问题《:subject》的申精费用', ['subject' => $question->subject]);
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
}
