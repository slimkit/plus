<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Question;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class QuestionController extends BaseController
{
    /**
     * 问答.
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function question(Request $request)
    {
        $this->PlusData['current'] = 'question';
        if ($request->isAjax) {
            $params = [
                'offset' => $request->query('offset', 0),
                'limit' => $request->query('limit', 0),
                'type' => $request->query('type', 'all'),
            ];
            $question['data'] = api('GET', '/api/v2/questions', $params);
            if ($params['type'] == 'excellent') {
                // TODO
                foreach ($question['data'] as $key => &$value) {
                    $value['excellent_show'] = false;
                }
            }
            $html = view('pcview::templates.question', $question, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'data' => $html,
            ]);
        }

        return view('pcview::question.index', [], $this->PlusData);
    }
}
