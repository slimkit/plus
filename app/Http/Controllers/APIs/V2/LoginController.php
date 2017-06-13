<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\AuthToken;
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
    public function store(Factory $factory, StoreLoginPost $request, ResponseFactory $response)
    {
        $phone = $request->input('phone');
        $account = $request->input('account');
        $password = $request->input('password');

        if ($phone) {
            $builder = User::byPhone($phone);
        } else {
            $builder = User::byAccount($account);
        }

        if (! $user = $builder->first()) {
            return $response->json([
                'message' => ['登录的用户不存在'],
            ])->setStatusCode(422);
        }

        if (! $user->verifyPassword($password)) {
            return $response->json([
                'password' => ['用户密码错误'],
            ])->setStatusCode(422);
        }

        return $response->json($factory->create(AuthToken::class, [
                'token' => str_random(64),
                'refresh_token' => str_random(64),
                'user_id' => $user->id,
            ]))
            ->setStatusCode(201);
    }
}
