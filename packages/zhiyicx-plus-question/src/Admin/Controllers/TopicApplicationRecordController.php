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
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\TopicApplication as TopicApplicationModel;

class TopicApplicationRecordController extends Controller
{
    /**
     * list of topic apply record.
     *
     * @param Request $request
     * @param TopicApplicationModel $topicApplicationModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, TopicApplicationModel $topicApplicationModel)
    {
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);
        $name = $request->query('name');
        $status = $request->query('status');

        $query = $topicApplicationModel->when($name, function ($query) use ($name) {
            return $query->where('name', 'like', '%'.$name.'%');
        })
        ->when(! is_null($status), function ($query) use ($status) {
            return $query->where('status', $status);
        });

        $total = $query->count('id');
        $records = $query->limit($limit)->offset($offset)->orderBy('id', 'desc')->get();

        return response()->json($records, 200, ['x-total' => $total]);
    }

    /**
     * accept a application.
     *
     * @param Request $request
     * @param TopicApplicationModel $topicApplication
     * @param Carbon $datetime
     * @param TopicModel $topicModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function accept(Request $request, TopicApplicationModel $topicApplication, Carbon $datetime, TopicModel $topicModel)
    {
        $user = $request->user();

        $topicApplication->examiner = $user->id;
        $topicApplication->expires_at = $datetime;
        $topicApplication->status = 1;

        $topicModel->name = $topicApplication->name;
        $topicModel->description = $topicApplication->description;

        $topicApplication->getConnection()->transaction(function () use ($topicApplication, $topicModel) {
            $topicModel->save();
            $topicApplication->save();

            $topicApplication->user->sendNotifyMessage('question-topic:accept', sprintf('你申请创建的话题%s已被管理员通过', $topicApplication->name), [
                'topic' => $topicModel,
            ]);
        });

        return response()->json($topicApplication, 201);
    }

    /**
     * reject a application.
     *
     * @param Request $request
     * @param TopicApplicationModel $topicApplication
     * @param Carbon $datetime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function reject(Request $request, TopicApplicationModel $topicApplication, Carbon $datetime)
    {
        $user = $request->user();

        $topicApplication->examiner = $user->id;
        $topicApplication->expires_at = $datetime;
        $topicApplication->status = 2;
        $topicApplication->remarks = $request->input('remarks');

        $topicApplication->getConnection()->transaction(function () use ($topicApplication) {
            $topicApplication->save();

            $topicApplication->user->sendNotifyMessage('question-topic:reject', sprintf('你申请创建的话题%s已被管理员驳回', $topicApplication->name), [
                'topic_application' => $topicApplication,
            ]);
        });

        return response()->json($topicApplication, 201);
    }

    /**
     * delete a application.
     *
     * @param  TopicApplicationModel $topicApplication
     * @return mixed
     */
    public function delete(TopicApplicationModel $topicApplication)
    {
        $topicApplication->delete();

        return response()->json(null, 204);
    }
}
