<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers;

use Github\Client as GitHub;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Github\Exception\RuntimeException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller as BaseController;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Access as AccessModel;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Middleware\CheckBindGitHub;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Project as ProjectModel;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\CreateProjectRequest;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\UpdateProjectRequest;

class ProjectsController extends BaseController
{
    /**
     * Create the Controller instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct() {
        $this->middleware(CheckBindGitHub::class)->only('readme', 'store');
    }

    /**
     * Get project readme.
     *
     * @param Request $request
     * @param ProjectModel $project
     * @return 
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function readme(Request $request, ProjectModel $project, GitHub $github): JsonResponse
    {
        $access = $request->user()->githubAccess;
        $github->authenticate($access->login, $access->password, GitHub::AUTH_HTTP_PASSWORD);

        $contents = $github->repos()->contents();
        $contents->configure('html');
        $readme = $contents->readme($project->github_owner, $project->github_repo);

        return response()->json(['readme' => $readme], 200);
    }

    /**
     * Get all projects.
     *
     * @return Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(): JsonResponse
    {
        $projects = $this->getProjectQuery()->get();

        return response()->json($projects, 200);
    }

    /**
     * Show a project.
     *
     * @param int $project
     * @return Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(int $project): JsonResponse
    {
        $project = $this->getProjectQuery()->find($project);
        if (! $project) {
            return response()->json(['message' => '项目不存在'], 404);
        }

        return response()->json($project, 200);
    }

    /**
     * Create a project.
     *
     * @param \Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\CreateProjectRequest $request
     * @param \Github\Client $github
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
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

        // 添加 GitHub 账号认证
        $access = $user->githubAccess;
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

    public function update(UpdateProjectRequest $request, ProjectModel $project): Response
    {
        $project->name = $request->input('name');
        $project->desc = $request->input('desc');
        $project->save();

        return response('', 204);
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
