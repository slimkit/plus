<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * 管理后台登录页面
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-03T17:16:07+0800
     * @return   [type]                     [description]
     */
    public function login()
    {
    	return view('admin/login');
    }


    public function  doLogin(Request $request)
    {
        $user = $request->attributes->get('user');
        session(['user_id' => $user->id, 'is_admin' => 1]);

        return app(MessageResponseBody::class, [
            'code' => 0,
        ])->setStatusCode(201);
    }

    public function index()
    {
        echo '';
    }
}
