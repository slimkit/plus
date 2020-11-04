<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Auth;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Authorize;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Session;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTAuth;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\VerificationCode;
use function Zhiyi\Plus\username;

class PassportController extends BaseController
{
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request, JWT $jwt)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        if ($token = $request->session()->get('token')) {
            $jwt->setToken($token);
            $jwt->invalidate();
        }

        return redirect(route('pc:feeds'));
    }

    /**
     * 登录.
     * @author Foreach
     * @return mixed
     */
    public function index()
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }

        return view('pcview::passport.login', [], $this->PlusData);
    }

    /**
     * 动态验证码登录.
     *
     * @return mixed
     */
    public function dynamic()
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }

        return view('pcview::passport.dynamiclogin', [], $this->PlusData);
    }

    /**
     * 验证码登录.
     * @author Foreach
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dynamicLogin(Request $request)
    {
        $login = (string) $request->input('login', '');
        $code = $request->input('verifiable_code');
        $field = username($login);

        if ($code !== null && in_array($field, ['phone', 'email'])) {
            $verify = VerificationCode::where('account', $login)
                ->where('channel', $field == 'phone' ? 'sms' : 'mail')
                ->where('code', $code)
                // ->byValid(120)
                ->orderby('id', 'desc')
                ->first();

            if (! $verify) {
                throw ValidationException::withMessages(['验证码错误或者已失效']);
            }

            $verify->delete();

            if ($user = User::query()->where($field, $login)->first()) {
                Authorize::login($user, true);

                return redirect(route('pc:feeds'));
            }

            throw ValidationException::withMessages([sprintf('%s还没有注册', $field == 'phone' ? '手机号' : '邮箱')]);
        }

        throw ValidationException::withMessages(['账号或验证码不正确']);
    }

    /**
     * 注册.
     * @author Foreach
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function register(Request $request)
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }

        $type = $request->input('type', 'phone');

        return view('pcview::passport.register', ['type' => $type], $this->PlusData);
    }

    /**
     * 找回密码
     * @author Foreach
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function findPassword(Request $request)
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }

        $type = $request->input('type', 'phone');

        return view('pcview::passport.findpwd', ['type' => $type], $this->PlusData);
    }

    /**
     * 完善资料.
     * @author Foreach
     * @return mixed
     */
    public function perfect()
    {
        $data['tags'] = api('GET', '/api/v2/tags');
        $data['user_tag'] = api('GET', '/api/v2/user/tags');

        return view('pcview::passport.perfect', $data, $this->PlusData);
    }

    /**
     * 图形验证码生成.
     * @author Foreach
     * @return mixed
     */
    public function captcha()
    {
        // 生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        // 设置背景
        $builder->setBackgroundColor(237, 237, 237);
        // 设置字体大小
        $builder->setBackgroundColor(237, 237, 237);
        // 可以设置图片宽高及字体
        $builder->build(/* width */ 100, /* height */ 40, /* font */ null);
        // 获取验证码的内容
        $phrase = $builder->getPhrase();

        // 把内容存入session
        Session::flash('milkcaptcha', $phrase);
        // 生成图片
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Type: image/jpeg');
        $builder->output();
    }

    /**
     * 图形验证码验证
     * @author Foreach
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCaptcha(Request $request)
    {
        $input = $request->input('captcha');
        if (Str::lower(Session::get('milkcaptcha')) === Str::lower($input)) {
            return response()->json([], 200);
        } else {
            return response()->json([], 501);
        }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * 通过token登录用户.
     * @param  Request  $request
     * @return mixed
     * @author Foreach
     */
    public function token(Request $request)
    {
        $token = $request->input('token');
        // 保存token
        $request->session()->put('token', $token);
        $request->session()->save();

        $jwt = app(JWTAuth::class);
        $user = $jwt->setToken($token)->authenticate();

        // 登录用户实例
        Authorize::login($user, true);

        return response()->json([], 200);
    }
}
