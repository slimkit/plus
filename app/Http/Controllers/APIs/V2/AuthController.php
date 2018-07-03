<?php

declare(strict_types=1);

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
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use function Zhiyi\Plus\username;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\VerificationCode;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard(): Guard
    {
        return Auth::guard('api');
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function login(Request $request): JsonResponse
    {
        $login = (string) $request->input('login', '');
        $code = $request->input('verifiable_code');
        $field = username($login);

        if ($code !== null && in_array($field, ['phone', 'email'])) {
            $verify = VerificationCode::where('account', $login)
                ->where('channel', $field == 'phone' ? 'sms' : 'mail')
                ->where('code', $code)
                ->byValid(120)
                ->orderby('id', 'desc')
                ->first();

            if (! $verify) {
                return $this->response()->json(['message' => '验证码错误或者已失效'], 422);
            }

            $verify->delete();

            if ($user = User::where($field, $login)->first()) {
                return $this->respondWithToken($this->guard()->login($user));
            }

            return $this->response()->json([
                'message' => sprintf('%s还没有注册', $field == 'phone' ? '手机号' : '邮箱'),
            ], 422);
        }

        $credentials = [
            $field => $login,
            'password' => $request->input('password', ''),
        ];

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return $this->response()->json(['message' => '账号或密码不正确'], 422);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function logout(): JsonResponse
    {
        $this->guard()->logout();

        return $this->response()->json(['message' => '退出成功']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(
            $this->guard()->refresh()
        );
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return $this->response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $this->guard()->factory()->getTTL(),
            'refresh_ttl' => config('jwt.refresh_ttl'),
        ]);
    }
}
