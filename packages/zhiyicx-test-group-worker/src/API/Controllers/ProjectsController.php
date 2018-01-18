<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers;

use Github\Client as GitHub;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Github\Exception\RuntimeException;
use Illuminate\Database\Eloquent\Builder;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Access as AccessModel;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Project as ProjectModel;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\CreateProjectRequest;

class ProjectsController
{
    /**
     * Get all projects.
     *
     * @return Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(): JsonResponse
    {
        $projects = $this->getProjectQuery()->all();

        return response()->json($projects, 200);
    }

    public function store(CreateProjectRequest $request, GitHub $github): JsonResponse
    {
        list($owner, $repo) = explode('/', $request->input('owner_repo'));
        $name = $request->input('name');
        $desc = $request->input('desc');
        $branch = $request->input('branch');
        $user = $request->user();

        // 检查是否以及被其他人添加
        $added = $this->getProjectQuery()
                      ->where('github_owner', $owner)
                      ->where('github_repo', $repo)
                      ->first();
        if ($added) {
            return response()->json(['message' => '该仓库已被其他人添加'], 422);
        }

        // 检查用户是否已添加 GitHub 账号
        if (! ($access = $this->getAccessQuery()->find($user->id))) {
            return response()->json(['message' => '请先进入「设置」绑定 GitHub 账号'], 422);
        }

        // 添加 GitHub 账号认证
        $github->authenticate($access->login, $access->password, GitHub::AUTH_HTTP_PASSWORD);

        // 检查仓库是否存在
        try {
            $github->repo()->show($owner, $repo);
        } catch (RuntimeException $e) {
            abort(422, '仓库不存在或者您没有该仓库权限');
        }

        // 检查分支是否存在
        $github->repo()->branches($owner, $repo, $branch);

        $project = new ProjectModel();
        $project->name = $name;
        $project->desc = $desc;
        $project->branch = $branch;
        $project->github_owner = $owner;
        $project->github_repo = $repo;
        $project->issues_count = 0;
        $project->task_count = 0;
        $project->creator = $user->id;
        $project->save();

        return response()->json(['id' => $project->id], 201);
    }

    /**
     * Get project model query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getProjectQuery(): Builder
    {
        return ProjectModel::query();
    }

    /**
     * Get access model query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getAccessQuery(): Builder
    {
        return AccessModel::query();
    }
}
