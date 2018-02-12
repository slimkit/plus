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
            'anonymity_rule' => config('question.anonymity_rule', '匿名规则'),
        ], 200);
    }
}
