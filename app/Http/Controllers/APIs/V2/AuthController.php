<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\VerificationCode;
use function Zhiyi\Plus\username;

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
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function login(Request $request): JsonResponse
    {
        $login = (string) $request->input('login', '');
        $code = $request->input('verifiable_code');
        $field = username($login);
        if ($code !== null && in_array($field, ['phone', 'email'])) {
            $verify = VerificationCode::query()->where('account', $login)
                ->where('channel', $field == 'phone' ? 'sms' : 'mail')
                ->where('code', $code)
                ->byValid(120)
                ->orderby('id', 'desc')
                ->first();

            if (! $verify) {
                return $this->response()
                    ->json(['message' => '验证码错误或者已失效'], 422);
            }

            $verify->delete();

            if ($user = User::withTrashed()->where($field, $login)->first()) {
                return ! $user->deleted_at
                    ?
                    $this->respondWithToken($this->guard()->login($user))
                    :
                    $this->response()->json([
                        'message' => '账号已被禁用，请联系管理员',
                    ], 403);
            }

            return $this->response()->json([
                'message' => sprintf('%s还没有注册',
                    $field == 'phone' ? '手机号' : '邮箱'),
            ], 422);
        }
        if ($user = User::withTrashed()
            ->where($field, $login)
            ->first()
        ) {
            if ($user->deleted_at) {
                return $this->response()->json([
                    'message' => '账号已被禁用，请联系管理员',
                ], 403);
            }
            $credentials = [
                $field => $login,
                'password' => $request->input('password', ''),
            ];

            if ($token = $this->guard()->attempt($credentials)) {
                return $this->respondWithToken($token);
            }

            return $this->response()->json(['message' => '账号或密码不正确'], 422);
        } else {
            return $this->response()->json([
                'message' => sprintf('%s还没有注册', $field == 'phone'
                    ? '手机号' : ($field === 'name' ? '账号' : '邮箱')),
            ], 422);
        }
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
     * @param  string  $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        $this->guard()->user()->update([
            'last_login_ip' => request()->ip(),
        ]);

        return $this->response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => $this->guard()->factory()->getTTL(),
            'refresh_ttl'  => config('jwt.refresh_ttl'),
        ]);
    }
}
