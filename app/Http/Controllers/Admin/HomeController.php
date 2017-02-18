<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\User;

class HomeController extends Controller
{
    use AuthenticatesUsers {
        login as traitLogin;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'index']]);
    }

    public function username()
    {
        return 'phone';
    }

    public function login(Request $request)
    {
        $this->guard()->logout();

        return $this->traitLogin($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect(route('admin'));
    }

    public function index()
    {
        $user = $this->guard()->user();
        $data = [
            'csrf_token' => csrf_token(),
            'base_url'   => url('admin'),
            'api'        => url('api/v1'),
            'logged'     => $this->guard()->check(),
            'user'       => $user ? $this->user($user) : null,
            'menu'       => $this->menu(),
        ];

        return view('admin', $data);
    }

    protected function menu()
    {
        return [
            // 'medz/plus-component-medz' => [
            //     'name'  => 'MEDZ',
            //     'admin' => 'http://medz.cn',
            //     'icon'  => 'http://medz.cn/windid/attachment/avatar/000/00/03/387_small.jpg',
            //     'logo'  => '',
            // ],
            // 'medz/plus-component-baidu' => [
            //     'name'  => '百度',
            //     'admin' => 'https://www.baidu.com',
            //     'icon'  => 'hhttps://www.baidu.com/favicon.ico',
            //     'logo'  => '',
            // ],
        ];
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $user
     *
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return response()->json($this->user($user));
    }

    protected function user(User $user)
    {
        $user->load('datas');

        return $user;
    }
}
