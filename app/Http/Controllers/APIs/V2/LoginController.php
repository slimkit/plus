<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\AuthToken;
use function Zhiyi\Plus\username;
use Illuminate\Database\Eloquent\Factory;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Zhiyi\Plus\Http\Requests\API2\StoreLoginPost;

class LoginController extends Controller
{
    /**
     * 用户登录接口.
     *
     * @param \Illuminate\Database\Eloquent\Factory $factory
     * @param \Zhiyi\Plus\Http\Requests\API2\StoreLoginPost $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Factory $factory, StoreLoginPost $request, ResponseFactory $response, User $model)
    {
        $login = $request->input('login');
        $password = $request->input('password');
        $username = username($login);
        $user = $model->where($username, $login)
            ->firstOrFail();

        if (! $user->verifyPassword($password)) {
            return $response->json([$username => ['账户密码错误']], 422);
        }

        return $response->json($factory->create(AuthToken::class, [
            'token' => str_random(64),
            'refresh_token' => str_random(64),
            'user_id' => $user->id,
        ]))->setStatusCode(201);
    }
}
