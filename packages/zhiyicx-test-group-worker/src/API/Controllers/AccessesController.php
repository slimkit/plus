<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers;

use Github\Client as GitHub;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Access as AccessModel;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\StoreGitHubAccessRequest;

class AccessesController
{
    /**
     * Get user GitHub accesses.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Packages\TestGroupWorker\Models\Access $model
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, AccessModel $model): JsonResponse
    {
        $user = $request->user();
        $accesses = $model->newQuery()->where('owner', $user->id)->get();

        return response()->json($accesses, 200);
    }

    /**
     * Show the user GitHub Access.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Packages\TestGroupWorker\Models\Access $model
     * @param string $access
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, AccessModel $model, string $access): JsonResponse
    {
        $user = $request->user();
        $access = $model->newQuery()
                        ->where('owner', $user->id)
                        ->where('username', $access)
                        ->firstOrFail();

        return response()->json($access, 200);
    }

    /**
     * Store a GitHub to auth user.
     *
     * @param \Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\StoreGitHubAccessRequest $request
     * @param \\Zhiyi\Plus\Packages\TestGroupWorker\Models\Access $model
     * @param \GitHub\Client $github
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreGitHubAccessRequest $request, AccessModel $model, GitHub $github): JsonResponse
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $user = $request->user();
        $existed = $model->newQuery()
                        ->where('owner', $user->id)
                        ->where('username', $username)
                        ->first();
        if ($existed) {
            return response()->json(['message' => '您已添加过该账号'], 422);
        }

        $access = new AccessModel();
        $access->owner = $user->id;
        $access->username = $username;
        $access->password = $password;

        $github->authenticate($username, $password, GitHub::AUTH_HTTP_PASSWORD);
        $me = $github->me()->show();

        // 矫正用户名，以便后续其他操作。
        $access->username = $me['login'];
        $access->save();
        
        return response()->json($access, 201);
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
