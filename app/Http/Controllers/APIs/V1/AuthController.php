<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\AuthToken;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\VerificationCode;
use Zhiyi\Plus\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * 发送手机验证码.
     *
     * @param Request $request 请求对象
     *
     * @return Response 返回对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function sendPhoneCode(Request $request)
    {
        $vaildSecond = config('app.env') == 'production' ? 300 : 6;
        $phone = $request->input('phone');
        $verify = VerificationCode::where('account', $account)
            ->byValid($vaildSecond)
            ->orderBy('id', 'desc')
            ->first();

        if ($verify) {
            return response()->json([
                'status'  => false,
                'code'    => 1008,
                'message' => null,
                'data'    => '请稍后再获取验证码',
            ])->setStatusCode(403);
        }

        $model = factory(VerificationCode::class)->create([
            'channel' => 'sms',
            'account' => $phone,
        ]);
        $model->notify(
             new \Zhiyi\Plus\Notifications\VerificationCode($model)
        );

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '获取成功',
        ]))->setStatusCode(201);
    }

    /**
     * 用户登录.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2016-12-27T16:57:18+0800
     *
     * @param Request $request 请求对象
     *
     * @return Response 响应对象
     */
    public function login(Request $request)
    {
        $phone = $request->input('phone');
        $password = $request->input('password', '');

        $user = User::byPhone($phone)->first();
        if (! $user->verifyPassword($password)) {
            return response()->json(static::createJsonData([
                'code' => 1006,
            ]))->setStatusCode(401);
        }

        $deviceCode = $request->input('device_code');
        $token = new AuthToken();
        $token->token = md5($deviceCode.str_random(32));
        $token->refresh_token = md5($deviceCode.str_random(32));
        $token->user_id = $user->id;
        $token->expires = 0;
        $token->state = 1;

        DB::transaction(function () use ($token, $user) {
            $user->tokens()->update(['state' => 0]);
            $user->tokens()->delete();
            $token->save();
        });

        //返回数据
        $data = [
            'token'         => $token->token,
            'refresh_token' => $token->refresh_token,
            'created_at'    => $token->created_at->getTimestamp(),
            'expires'       => $token->expires,
            'user_id'       => $user->id,
        ];

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '登录成功',
            'data'    => $data,
        ])->setStatusCode(201);
    }

    /**
     * 重置token.
     *
     * @param Request $request 请求对象
     *
     * @return Response 返回对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function resetToken(Request $request)
    {
        $shutDownState = 0;
        $refresh_token = $request->input('refresh_token');

        if (! $refresh_token || ! ($token = AuthToken::withTrashed()->byRefreshToken($refresh_token)->orderByDesc()->first())) {
            return response()
                ->json([
                    'status'  => false,
                    'code'    => 1016,
                    'message' => '操作失败',
                    'data'    => null,
                ])->setStatusCode(404);
        } elseif ($token->state === $shutDownState) {
            return response()->json([
                'status'  => false,
                'code'    => 1013,
                'message' => '请重新登录',
                'data'    => null,
            ]);
        }

        DB::transaction(function () use ($token, $shutDownState) {
            $token->state = $shutDownState;
            $token->save();
        });

        return $this->login($request);
    }

    /**
     * 注册用户.
     *
     * @param Request $request 请求对象
     *
     * @return Response 返回对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function register(Request $request)
    {
        $name = $request->input('name');
        $phone = $request->input('phone');
        $password = $request->input('password', '');
        $user = new User();
        $user->name = $name;
        $user->phone = $phone;
        $user->createPassword($password);

        $role = CommonConfig::byNamespace('user')
        ->byName('default_role')
        ->firstOr(function () {
            throw new \RuntimeException('Failed to get the defined user group.');
        });

        DB::transaction(function () use ($user, $role) {
            $user->save();

            // 添加默认用户组.
            $user->attachRole($role->value);
        });

        return $this->login($request);
    }

    /**
     * 找回密码控制器.
     *
     * @param Request $request 请求对象
     *
     * @return any 返回对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function forgotPassword(Request $request)
    {
        $password = $request->input('password', '');
        $user = $request->attributes->get('user');
        $user->createPassword($password);
        $user->save();

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '重置密码成功',
            'data'    => null,
        ])->setStatusCode(201);
    }
}
