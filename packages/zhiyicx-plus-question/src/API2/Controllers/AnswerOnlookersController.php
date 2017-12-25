<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\WalletCharge;
use SlimKit\PlusQuestion\Models\Answer;
use Illuminate\Contracts\Routing\ResponseFactory;

class AnswerOnlookersController extends Controller
{
    // Application amount in background settings
    protected $onlookers_amount;

    public function __construct()
    {
        $this->onlookers_amount = config('question.onlookers_amount');
    }

    /**
     * onlookers a question.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answer
     * @param  Zhiyi\Plus\Models\WalletCharge $charge
     * @param  Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function store(Request $request, Answer $answer, WalletCharge $charge, ResponseFactory $response)
    {
        $user = $request->user();
        $target = $answer->question->user;

        if (! $answer->question->look || $user->id === $answer->user_id || $user->id === $answer->question->user_id) {
            return $response->json(['message' => [trans('plus-question::questions.onlookers.unnecessary')]], 422);
        }

        if ($answer->onlookers()->where('id', $user->id)->first()) {
            return $response->json(['message' => [trans('plus-question::questions.onlookers.already-exists')]], 422);
        }

        if (! $user->wallet || $user->wallet->balance < $this->onlookers_amount) {
            return response()->json(['message' => [trans('plus-question::questions.Insufficient balance')]], 422);
        }

        $user->getConnection()->transaction(function () use ($user, $answer, $charge, $target) {
            // 扣除围观费用
            $user->wallet()->decrement('balance', $this->onlookers_amount);

            // 保存扣费凭据
            $user_charge = clone $charge;
            $user_charge->user_id = $user->id;
            $user_charge->channel = 'user';
            $user_charge->account = $target->id;
            $user_charge->action = 0;
            $user_charge->amount = $this->onlookers_amount;
            $user_charge->subject = trans('plus-question::questions.onlookers.支付问题围观答案费用');
            $user_charge->body = trans('plus-question::questions.onlookers.支付问题《:subject》的围观答案费用', ['subject' => $answer->question->subject]);
            $user_charge->status = 1;
            $user_charge->save();

            // 问题发布者收入
            $target->wallet()->increment('balance', $this->onlookers_amount);

            // 保存收入凭据
            $charge->user_id = $target->id;
            $charge->channel = 'user';
            $charge->account = $user->id;
            $charge->action = 1;
            $charge->amount = $this->onlookers_amount;
            $charge->subject = trans('plus-question::questions.onlookers.收到问题围观答案费用');
            $charge->body = trans('plus-question::questions.onlookers.收到问题《:subject》的围观答案费用', ['subject' => $answer->question->subject]);
            $charge->status = 1;
            $charge->save();

            // 给问题所有者发布通知
            $message = trans('plus-question::questions.onlookers.收到问题《:subject》的围观答案费用', ['subject' => $answer->question->subject]);
            $target->sendNotifyMessage('question:answer-onlooker', $message, [
                'user' => $user,
                'question' => $answer->question,
                'answer' => $answer,
            ]);

            // 保存围观记录
            $answer->onlookers()->attach($user->id, ['amount' => $this->onlookers_amount]);
        });

        $answer->load('user');
        $answer->onlookers_count = $answer->onlookers()->count();
        $answer->onlookers_total = $answer->onlookers()->withPivot('amount')->sum('amount');
        if ($answer->anonymity && $answer->user_id !== $user->id) {
            $answer->addHidden('user');
            $answer->user_id = 0;
        }

        return response()->json([
            'message' => [trans('plus-question::messages.success')],
            'answer' => $answer,
        ], 201);
    }
}
