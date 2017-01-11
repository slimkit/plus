<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'login';

    /**
     * 管理后台登录页面.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-03T17:16:07+0800
     *
     * @return [type] [description]
     */
    public function login()
    {

        return view('admin.login');
    }

    /**
     * 后台登录方法.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-06T09:45:55+0800
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function doLogin(Request $request)
    {
        $user = $request->attributes->get('user');
        session(['user_id' => $user->id, 'is_admin' => 1]);

        return app(MessageResponseBody::class, [
            'status'  => true,
            'message' => '登录成功',
            'data'    => [
                'jumpUrl' => '',
            ],
        ])->setStatusCode(201);
    }

    /**
     * 后台登出方法.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-06T09:45:43+0800
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function logout(Request $request)
    {
        session(['is_admin' => 0]);
        return redirect(route('admin.login'));
    }

    public function index(Request $request)
    {
        return view('admin.index');
    }
}
