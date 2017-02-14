<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;

class HomeController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'phone';
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        return parent::login();
    }

    public function index()
    {
        $data = [
            'csrf_token' => csrf_token(),
            'base_url'   => url('admin'),
            'logged'     => $this->guard()->check(),
            'user'       => $this->guard()->user(),
        ];

        return view('admin', $data);
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
        return response()->json($user);
    }
}
