<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Task as TaskModel;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\CreateTaskRequest;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Project as ProjectModel;

class TesksController extends BaseController
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
