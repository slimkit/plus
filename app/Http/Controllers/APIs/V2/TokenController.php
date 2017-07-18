<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\AuthToken;
use function Zhiyi\Plus\username;
use Illuminate\Database\Eloquent\Factory;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Zhiyi\Plus\Http\Requests\API2\StoreLoginPost;
use Tymon\JWTAuth\JWTAuth;

class TokenController extends Controller
{
    /**
     * Create a user token
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
                'refresh_ttl' => config('jwt.refresh_ttl')
            ])->setStatusCode(201)
            : $response->json(['message' => 'Failed to create token.'])->setStatusCode(500);
    }
}
