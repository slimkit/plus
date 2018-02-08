<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

class QuestionConfigController extends Controller
{
    /**
     * 获取问答相关配置.
     *
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function get()
    {
        return response()->json([
            'apply_amount' => config('question.apply_amount', 200),
            'onlookers_amount' => config('question.onlookers_amount', 100),
            'anonymity_rule' => config('question.anonymity_rule', '匿名规则')
        ], 200);
    }
}
