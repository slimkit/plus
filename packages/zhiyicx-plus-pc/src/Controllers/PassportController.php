<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Auth;
use Session;
use Cookie;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Auth as Authorize;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class PassportController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        // Run parent construct method.
        parent::__construct();

        // Register "guest" middleware
        $this->middleware('guest')->except('logout', 'perfect', 'captcha', 'checkCaptcha');
    }

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
     * 登录
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
     * 注册
     * @author Foreach
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $type = $request->input('type', 'phone');
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }

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
        $type = $request->input('type', 'phone');
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }
        
        return view('pcview::passport.findpwd', ['type' => $type], $this->PlusData);
    }

    /**
     * 完善资料
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
     * 图形验证码生成
     * @author Foreach
     * @return mixed
     */
    public function captcha()
    {
        // 生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        // 设置背景
        $builder->setBackgroundColor(237,237,237);
        // 设置字体大小
        $builder->setBackgroundColor(237,237,237);
        // 可以设置图片宽高及字体
        $builder->build($width = 100, $height = 40, $font = null);
        // 获取验证码的内容
        $phrase = $builder->getPhrase();

        // 把内容存入session
        Session::flash('milkcaptcha', $phrase);
        // 生成图片
        header("Cache-Control: no-cache, must-revalidate");
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

        if (Session::get('milkcaptcha') == $input) {
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
     * 通过token登录用户
     * @author Foreach
     * @return mixed
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
