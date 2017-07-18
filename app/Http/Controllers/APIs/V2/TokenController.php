<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Tymon\JWTAuth\JWTAuth;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use function Zhiyi\Plus\username;
use Zhiyi\Plus\Http\Controllers\Controller;
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
    public function store(Request $request, ResponseFactoryContract $response, JWTAuth $auth)
    {
        $login = $request->input('login');
        $password = $request->input('password');
        $credentials = [
            username($login) => $login,
            'password' => $password,
        ];

        return ($token = $auth->attempt($credentials)) !== false
            ? $response->json([
                'token' => $token,
                'user' => $request->user(),
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
