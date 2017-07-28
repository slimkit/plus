<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Tymon\JWTAuth\JWTAuth;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use function Zhiyi\Plus\username;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TokenController extends Controller
{
    /**
     * Create a user token.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Tymon\JWTAuth\JWTAuth $auth
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, ResponseFactoryContract $response, JWTAuth $auth, User $model)
    {
        $login = $request->input('login');
        $password = $request->input('password');
        $user = $model->where(username($login), $login)->with('wallet')->first();

        if (! $user) {
            return $response->json(['login' => ['用户不存在']], 404);
        } elseif (! $user->verifyPassword($password)) {
            return $response->json(['password' => ['密码错误']], 422);
        }

        return ($token = $auth->fromUser($user)) !== false
            ? $response->json([
                'token' => $token,
                'user' => array_merge($user->toArray(), [
                    'phone'  => $user->phone,
                    'email'  => $user->email,
                    'wallet' => $user->wallet ? $user->wallet->balance : null,
                ]),
                'ttl' => config('jwt.ttl'),
                'refresh_ttl' => config('jwt.refresh_ttl'),
            ])->setStatusCode(201)
            : $response->json(['message' => ['Failed to create token.']])->setStatusCode(500);
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
    public function refresh(ResponseFactoryContract $response, JWTAuth $auth, string $token)
    {
        return ($token = $auth->refresh($token)) !== false
            ? $response->json([
                'token' => $token,
                'ttl' => config('jwt.ttl'),
                'refresh_ttl' => config('jwt.refresh_ttl'),
            ])->setStatusCode(201)
            : $response->json(['message' => ['Failed to refresh token.']], 500);
    }
}
