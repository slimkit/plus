<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Middleware\CheckBindGitHub;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Project as ProjectModel;

class ProjectIssuesController extends BaseController
{
    /**
     * Create the Controller instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct() {
        $this->middleware(CheckBindGitHub::class);
    }

    public function index(Request $request, ProjectModel $project): JsonResponse
    {
        // todo.
    }
}
