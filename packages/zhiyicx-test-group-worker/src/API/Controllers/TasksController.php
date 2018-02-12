<?php

declare(strict_types=1);

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

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Task as TaskModel;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\CreateTaskRequest;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Project as ProjectModel;

class TasksController extends BaseController
{
    public function all(Request $request): JsonResponse
    {
        $state = $request->query('state', 'open');
        $tasks = TaskModel::where('state', $state)->get();

        return response()->json($tasks, 200);
    }

    public function index(Request $request, ProjectModel $project): JsonResponse
    {
        $tasks = TaskModel::where('project_id', $project->id)->get();

        return response()->json($tasks, 200);
    }

    public function store(CreateTaskRequest $request, ProjectModel $project): JsonResponse
    {
        $task = new TaskModel();
        $task->title = $request->input('title');
        $task->desc = $request->input('desc', '');
        $task->version = $request->input('version');
        $task->start_at = $request->input('start_at');
        $task->end_at = $request->input('end_at');
        $task->creator = $request->user()->id;
        $task->project_id = $project->id;
        $task->save();

        return response()->json(['message' => '创建成功', 'task_id' => $task->id], 201);
    }
}
