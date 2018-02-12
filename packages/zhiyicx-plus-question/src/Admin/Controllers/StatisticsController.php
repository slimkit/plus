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
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use SlimKit\PlusQuestion\Models\AnswerOnlooker as AnswerOnlookerModel;

class StatisticsController extends Controller
{
    /**
     * 获取所有统计信息.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request)
    {
        $handles = [
            'all' => function ($query) {
                return $query;
            },
            'today' => function ($query) {
                return $query->whereDate('created_at',
                    Carbon::now()->format('Y-m-d')
                );
            },
            'yesterday' => function ($query) {
                return $query->whereDate('created_at',
                    Carbon::now()->subDay(1)->format('Y-m-d')
                );
            },
            'week' => function ($query) {
                return $query->whereDate(
                    'created_at', '>',
                    Carbon::now()->subWeek(1)->format('Y-m-d')
                );
            },
        ];
        $type = in_array($type = $request->query('type'), array_keys($handles)) ? $type : 'all';

        $querys = [
            'question' => function (callable $handler) {
                return $handler(QuestionModel::query())->count('id');
            },
            'answer' => function (callable $handler) {
                return $handler(AnswerModel::query())->count('id');
            },
            'invitation' => function (callable $handler) {
                return $handler(QuestionModel::where('automaticity', '!=', 0))->count('id');
            },
            'public' => function (callable $handler) {
                return $handler(QuestionModel::where('amount', '!=', 0))->count('id');
            },
            'iamount' => function (callable $handler) {
                return $handler(QuestionModel::where('automaticity', '!=', 0))->sum('amount');
            },
            'pamount' => function (callable $handler) {
                return $handler(QuestionModel::where('amount', '!=', 0))->sum('amount');
            },
            'reward' => function (callable $handler) {
                return $handler(AnswerOnlookerModel::query())->sum('amount');
            },
        ];

        return response()->json(array_map(function (callable $handler) use ($handles, $type) {
            return $handler($handles[$type]);
        }, $querys), 200);
    }
}
