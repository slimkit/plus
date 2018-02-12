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

namespace SlimKit\PlusQuestion\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use SlimKit\PlusQuestion\Models\QuestionApplication as QuestionApplicationModel;

class QuestionApplicationRecordController extends Controller
{
    /**
     * 查看申请精选问题列表.
     *
     * @param Request $request
     * @param QuestionApplicationModel $questionApplicationModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, QuestionApplicationModel $questionApplicationModel)
    {
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);
        $question = $request->query('question');
        $user = $request->query('user');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status');

        $query = $questionApplicationModel->when($question, function ($query) use ($question) { // 根据问题id查询
            return $query->where('question_id', $question);
        })
        ->when($user, function ($query) use ($user) { // 根据申请用户查询
            return $query->where('user_id', $user);
        })
        ->when($startDate, function ($query) use ($startDate) { // 根据起始时间筛选
            return $query->whereDate('created_at', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) { // 根据截至时间筛选
            return $query->whereDate('created_at', '<=', $endDate);
        })->whereExists(function ($query) {
            return $query->from('questions')->whereRaw('questions.id = question_application.question_id')->where('deleted_at', null);
        })
        ->when(! is_null($status), function ($query) use ($status) {
            return $query->where('status', $status);
        });

        $total = $query->count('id');
        $applications = $query->offset($offset)->limit($limit)->with(['question', 'user', 'examiner'])->orderBy('id', 'desc')->get();

        return response()->json($applications, 200, ['x-total' => $total]);
    }

    /**
     * 通过加精申请.
     *
     * @param Request $request
     * @param QuestionApplicationModel $application
     * @param Carbon $datetime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function accept(Request $request, QuestionApplicationModel $application, Carbon $datetime)
    {
        $user = $request->user();

        if ($application->expires_at !== null || $application->status !== 0) {
            return response()->json(['message' => '该条记录已被处理，请勿重复操作'], 422);
        }

        $application->expires_at = $datetime;
        $application->examiner = $user->id;
        $application->status = 1;

        $application->getConnection()->transaction(function () use ($application) {
            $application->question->excellent = 1;

            $application->question->save();
            $application->save();

            $application->user->sendNotifyMessage('question-excellent:accept', sprintf('你的问题%s已被管理员加精', $application->question->subject), [
                'application' => $application,
            ]);
        });

        return response()->json(['message' => '操作成功'], 204);
    }

    /**
     * 拒绝加精申请.
     *
     * @param Request $request
     * @param QuestionApplicationModel $application
     * @param Carbon $datetime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function reject(Request $request, QuestionApplicationModel $application, Carbon $datetime)
    {
        $user = $request->user();

        if ($application->expires_at !== null || $application->status !== 0) {
            return response()->json(['message' => '该条记录已被处理，请勿重复操作'], 422);
        }

        $application->expires_at = $datetime;
        $application->examiner = $user->id;
        $application->status = 2;

        $application->getConnection()->transaction(function () use ($application) {
            $charge = new WalletChargeModel();
            $charge->user_id = $application->user->id;
            $charge->channel = 'system';
            $charge->action = 1;
            $charge->amount = $application->amount;
            $charge->subject = trans('plus-question::questions.application.退还问题申精费用');
            $charge->body = trans('plus-question::questions.application.退还问题《:subject》的申精费用', ['subject' => $application->question->subject]);
            $charge->status = 1;
            $charge->save();

            $application->user->wallet->increment('balance', $application->amount);
            $application->save();

            $application->user->sendNotifyMessage('question-excellent:reject', sprintf('问题%s的加精申请已被管理员拒绝', $application->question->subject), [
                'application' => $application,
            ]);
        });

        return response()->json(['message' => '操作成功'], 204);
    }
}
