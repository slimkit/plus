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

use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\TopicApplication as TopicApplicationModel;
use SlimKit\PlusQuestion\API2\Requests\TopicApplication as TopicApplicationRequest;

class TopicApplicationController extends Controller
{
    /**
     * @author bs<414606094@qq.com>
     * @param TopicApplicationRequest $request
     * @param TopicApplicationModel   $topicApplicationModel
     * @param TopicModel              $topicModel
     * @return mixed
     */
    public function store(TopicApplicationRequest $request, TopicApplicationModel $topicApplicationModel, TopicModel $topicModel)
    {
        $user = $request->user();
        $name = $request->input('name');
        $description = $request->input('description');
        if ($topicModel->where('name', '=', $name)->first() || $topicApplicationModel->where('name', '=', $name)->first()) {
            return response()->json(['message' => trans('plus-question::topics.applied')], 422);
        }

        $topicApplicationModel->user_id = $user->id;
        $topicApplicationModel->name = $name;
        $topicApplicationModel->description = $description;
        try {
            $topicApplicationModel->save();
        } catch (\Exception $e) {
            \Log::debug($e);
            return response()->json(['message' => '申请创建话题失败'], 500);
        }

        return response()->json([
            'message' => trans('plus-question::messages.success'),
        ], 201);
    }
}
