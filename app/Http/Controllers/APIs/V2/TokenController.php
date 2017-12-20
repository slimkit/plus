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
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use function Zhiyi\Plus\username;
use Zhiyi\Plus\Auth\JWTAuthToken;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TokenController extends Controller
{
    /**
     * Create a user token.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Auth\JWTAuthToken $jwtAuthToken
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, ResponseFactoryContract $response, JWTAuthToken $jwtAuthToken, User $model)
    {
        $login = $request->input('login', '');
        $password = $request->input('password', '');

        $user = $model->where(username($login), $login)->with('wallet')->withCount('administrator')->first();

        if (! $user) {
            return $response->json(['login' => ['用户不存在']], 404);
        } elseif (! $user->verifyPassword($password)) {
            return $response->json(['password' => ['密码错误']], 422);
        } elseif ($user->roles->whereStrict('id', 3)->isNotEmpty()) { // 禁止登录用户
            return $response->json(['message' => ['你已被禁止登陆']], 422);
        } elseif (($token = $jwtAuthToken->create($user))) {
            return $response->json([
                'token' => $token,
                'user' => array_merge($user->toArray(), [
                    'phone'  => $user->phone,
                    'email'  => $user->email,
                    'wallet' => $user->wallet,
                ]),
                'ttl' => config('jwt.ttl'),
                'refresh_ttl' => config('jwt.refresh_ttl'),
            ])->setStatusCode(201);
        }

        return $response->json(['message' => ['Failed to create token.']])->setStatusCode(500);
    }

    /**
     * Refresh a user token.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Tymon\JWTAuth\JWTAuth $auth
     * @param string $token
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function refresh(ResponseFactoryContract $response, JWTAuthToken $jwtAuthToken, string $token)
    {
        if (! ($token = $jwtAuthToken->refresh($token))) {
            return $response->json(['message' => ['Failed to refresh token.']], 500);
        }

        return $response->json([
            'token' => $token,
            'ttl' => config('jwt.ttl'),
            'refresh_ttl' => config('jwt.refresh_ttl'),
        ])->setStatusCode(201);
    }
}
