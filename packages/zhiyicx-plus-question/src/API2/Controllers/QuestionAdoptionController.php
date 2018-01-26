<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class QuestionAdoptionController extends Controller
{
    /**
     * Attach a adoption answer for question.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @param \SlimKit\PlusQuestion\Models\Answer $answer
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request,
                          ResponseFactoryContract $response,
                          QuestionModel $question,
                          AnswerModel $answer)
    {
        $user = $request->user();
        $answer->load('user');

        // Is't the question owner?
        if ($question->user_id !== $user->id) {
            return $response->json(['message' => [trans('plus-question::questions.adoption.not-owner')]], 403);

            // Is the answer to this question?
        } elseif ($answer->question_id !== $question->id) {
            return $response->json(['message' => [trans('plus-question::questions.adoption.non')]], 422);

            // Are the questions and answers the owners?
        } elseif ($answer->user_id === $question->user_id) {
            return $response->json(['message' => [trans('plus-question::questions.adoption.own-answer')]], 422);

            // Have you ever submitted a question or has this question already answered?
        } elseif ($answer->adoption || ($adoption = $question->answers()->where('adoption', 1)->first())) {
            return $response->json(['message' => [trans('plus-question::questions.adoption.already')]], 422);
        }

        $unautomaticity = $question && ! $question->automaticity && $question->amount;
        $answer->adoption = 1;
        $question->status = 1;

        $question->getConnection()->transaction(function () use ($unautomaticity, $question, $answer) {
            $question->save();
            $answer->save();

            if ($unautomaticity) {
                $charge = new WalletChargeModel();
                $charge->user_id = $answer->user_id;
                $charge->channel = 'user';
                $charge->account = $question->user_id;
                $charge->action = 1;
                $charge->amount = $question->amount;
                $charge->subject = trans('plus-question::questions.adoption.charge-subject');
                $charge->body = $charge->subject;
                $charge->status = 1;

                $charge->save();
                $answer->user->wallet()->increment('balance', $charge->amount);
            }
        });

        $message = trans('plus-question::questions.adoption.notify-message');
        $answer->user->sendNotifyMessage('question:answer-adoption', $message, [
            'user' => $user,
            'question' => $question,
            'answer' => $answer,
        ]);

        return $response->json(['message' => [trans('plus-question::messages.success')]], 201);
    }

    /**
     * 消耗积分采纳答案.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @param \SlimKit\PlusQuestion\Models\Answer $answer
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request,
                          ResponseFactoryContract $response,
                          QuestionModel $question,
                          AnswerModel $answer)
    {
        $user = $request->user();
        $answer->load('user');

        // Is't the question owner?
        if ($question->user_id !== $user->id) {
            return $response->json(['message' => [trans('plus-question::questions.adoption.not-owner')]], 403);

            // Is the answer to this question?
        } elseif ($answer->question_id !== $question->id) {
            return $response->json(['message' => [trans('plus-question::questions.adoption.non')]], 422);

            // Are the questions and answers the owners?
        } elseif ($answer->user_id === $question->user_id) {
            return $response->json(['message' => [trans('plus-question::questions.adoption.own-answer')]], 422);

            // Have you ever submitted a question or has this question already answered?
        } elseif ($answer->adoption || ($adoption = $question->answers()->where('adoption', 1)->first())) {
            return $response->json(['message' => [trans('plus-question::questions.adoption.already')]], 422);
        }

        $unautomaticity = $question && ! $question->automaticity && $question->amount;
        $answer->adoption = 1;
        $question->status = 1;

        $question->getConnection()->transaction(function () use ($unautomaticity, $question, $answer) {
            $question->save();
            $answer->save();

            if ($unautomaticity) {
                $process = new UserProcess();
                $process->receivables($answer->user_id, $question->amount, $question->user_id, trans('plus-question::questions.adoption.charge-subject'), trans('plus-question::questions.adoption.charge-subject'));
            }
        });

        $message = trans('plus-question::questions.adoption.notify-message');
        $answer->user->sendNotifyMessage('question:answer-adoption', $message, [
            'user' => $user,
            'question' => $question,
            'answer' => $answer,
        ]);

        return $response->json(['message' => [trans('plus-question::messages.success')]], 201);
    }
}
