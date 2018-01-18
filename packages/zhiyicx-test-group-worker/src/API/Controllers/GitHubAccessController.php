<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers;

use Github\Client as GitHub;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Access as AccessModel;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\BindGitHubAccessRequest;

class GitHubAccessController
{
    /**
     * Bind a GitHub access to user.
     *
     * @param \Zhiyi\Plus\Packages\TestGroupWorker\API\Requests\BindGitHubAccessRequest $request
     * @param \Github\Client $github
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function bind(BindGitHubAccessRequest $request, GitHub $github): JsonResponse
    {
        $login = $request->input('login');
        $password = $request->input('password');
        $user = $request->user();
        $access = $this->getAccessQuery()
                       ->where('login', $login)
                       ->first();

        if ($access && $access->owner !== $user->id) {
            return response()->json(['message' => '该 GitHub 已绑定其他账号'], 422);
        } elseif (! ($access = $this->getAccessQuery()->find($user->id))) {
            $access = new AccessModel();
            $access->owner = $user->id;
        }

        $github->authenticate($login, $password, GitHub::AUTH_HTTP_PASSWORD);
        $me = $github->me()->show();

        $access->login = $me['login'];
        $access->password = $password;
        $access->save();

        return response()->json(['message' => "绑定 GitHub 账号：{$access->login} 成功", 'username' => $access->login], 201);
    }

    public function unbind(Request $request): Response
    {
        $user = $request->user();
        $this->getAccessQuery()->where('owner', $user->id)->delete();

        return response('', 204);
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
