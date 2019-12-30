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

namespace Zhiyi\Plus\Http\Controllers\Auth;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\User;
use function Zhiyi\Plus\username;

class LoginController extends Controller
{
    use AuthenticatesUsers {
        login as authenticatesUsersLogin;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showLoginForm(Repository $config)
    {
        return view('auth.login', [
            'login' => $login = old('email', old('phone', old('name', old('id', '')))),
            'errorUsername' => username($login),
            'logo' => $config->get('site.background.logo', url('/plus.png')),
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function login(Request $request)
    {
        $request->merge([
            $this->username() => $request->input('login'),
        ]);

        $user = User::withTrashed()
            ->where($this->username(), $request->input('login'))
            ->whereNotNull('deleted_at')
            ->first();
        if ($user) {
            throw ValidationException::withMessages(['账号已被禁用，请联系管理员']);
        }

        return $this->authenticatesUsersLogin($request);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function username(): string
    {
        return username(
            request()->input('login')
        );
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function redirectTo(): string
    {
        return request()->input('redirect') ?: '/';
    }
}
