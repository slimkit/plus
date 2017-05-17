<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhuzhichao\IpLocationZh\Ip;
use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\Plus\Services\SMS\SMS;
use Zhiyi\Plus\Models\VerifyCode;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Models\LoginRecord;
use Illuminate\Database\Eloquent\Factory;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Requests\API2\VerifyRegisterPost;

class AuthController extends Controller
{
    protected function findIp($ip): array
    {
        return (array) Ip::find($ip);
    }

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
    public function sendPhoneCode(Request $request, SMS $sms)
    {
        $phone = $request->input('phone');
        $vaildSecond = config('app.env') == 'production' ? 300 : 6;
        $verify = VerifyCode::byAccount($phone)->byValid($vaildSecond)->orderByDesc()->first();

        if ($verify) {
            return response()->json($verify->makeSurplusSecond($vaildSecond))->setStatusCode(403);
        }

        $verify = new VerifyCode();
        $verify->account = $phone;
        $verify->makeVerifyCode();
        $verify->save();

        $sms->send($verify);

        return response()->json()->setStatusCode(201);
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
            return response()->json([
                'message' => '密码错误',
            ])->setStatusCode(401);
        }

        $deviceCode = $request->input('device_code');
        $token = new AuthToken();
        $token->token = md5($deviceCode.str_random(32));
        $token->refresh_token = md5($deviceCode.str_random(32));
        $token->user_id = $user->id;
        $token->expires = 0;
        $token->state = 1;

        // 登录记录
        $clientIp = $request->getClientIp();
        $loginrecord = new LoginRecord();
        $loginrecord->ip = $clientIp;

        // 保留测试ip
        // $location = (array)Ip::find($clientIp);
        $location = $this->findIp('61.139.2.69');
        array_filter($location);
        $loginrecord->address = trim(implode(' ', $location));
        $loginrecord->device_system = $request->input('device_system');
        $loginrecord->device_name = $request->input('device_name');
        $loginrecord->device_model = $request->input('device_model');
        $loginrecord->device_code = $deviceCode;

        DB::transaction(function () use ($token, $user, $loginrecord) {
            $user->tokens()->update(['state' => 0]);
            $user->tokens()->delete();
            $token->save();
            $user->loginRecords()->save($loginrecord);
        });

        //返回数据
        $data = [
            'token'         => $token->token,
            'refresh_token' => $token->refresh_token,
            'created_at'    => $token->created_at->getTimestamp(),
            'expires'       => $token->expires,
            'user_id'       => $user->id,
        ];

        return response()->json($data)->setStatusCode(201);
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
            return response()->json([
                'message' => '操作失败',
            ])->setStatusCode(404);
        } elseif ($token->state === $shutDownState) {
            return response()->json([
                'message' => '请重新登录',
            ])->setStatusCode(403);
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
    public function register(VerifyRegisterPost $request, Factory $factory)
    {
        $name = $request->input('name');
        $phone = $request->input('phone');
        $password = $request->input('password');
        $code = $request->input('code');

        $vaild = 300;
        $verify = VerifyCode::byAccount($phone)
            ->byValid($vaild)
            ->byCode($code)
            ->orderByDesc()
            ->first();

        if (! $verify || $verify->state == 2) {
            return response()->json([
                'code' => ['验证码错误或失效'],
            ])->setStatusCode(403);
        }

        if (User::byPhone($phone)->withTrashed()->first()) {
            return response()->json([
                'phone' => ['手机号已被使用'],
            ])->setStatusCode(403);
        }

        if (User::byName($name)->withTrashed()->first()) {
            return response()->json([
                'name' => ['用户名已被使用'],
            ])->setStatusCode(403);
        }

        $user = new User();
        $user->name = $name;
        $user->phone = $phone;
        $user->createPassword($password);
        $user->save();

        return response()->json($factory->create(AuthToken::class, [
                'token' => str_random(64),
                'refresh_token' => str_random(64),
                'user_id' => $user->id,
            ]))
            ->setStatusCode(201);
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
            'message' => '重置密码成功',
        ])->setStatusCode(201);
    }
}
