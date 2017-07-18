<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use function Zhiyi\Plus\username;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class HomeController extends Controller
{
    use AuthenticatesUsers {
        login as traitLogin;
    }

    /**
     * @var string
     */
    protected $loginField;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'index', 'showManages']]);
    }

    /**
     * @return string
     */
    public function username()
    {
        return username(
            request()->input('account')
        );
    }

    public function login(Request $request)
    {
        $this->guard()->logout();
        $request->session()->regenerate();
        $request->merge([
            $this->username() => $request->input('account'),
        ]);

        return $this->traitLogin($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect(route('admin'));
    }

    /**
     * Admin home.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request)
    {
        $this->abortIfAuthenticated($user = $this->guard()->user(), $request);

        $data = [
            'csrf_token' => csrf_token(),
            'base_url'   => url('admin'),
            'api'        => url('api/v1'),
            'logged'     => $this->guard()->check(),
            'user'       => $user ? $this->user($user) : null,
        ];

        return view('admin', $data);
    }

    /**
     * 后台导航菜单.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showManages(ManageRepository $repository)
    {
        return response()
            ->json($repository->getManages())
            ->setStatusCode(200);
    }

    /**
     *  如果用户存在，判断权限.
     *
     * @param null|\Zhiyi\Plus\Models\User $user
     * @param \Illuminate\Http\Request $request
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function abortIfAuthenticated($user, Request $request)
    {
        if ($user && ! $user->can('admin:login')) {
            abort(401, '你没有权限访问该页面');
        }
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
        if (! $user->can('admin:login')) {
            $this->guard()->logout();
            $request->session()->regenerate();

            return response()->json([
                'phone' => '该账户没有权限进入后台',
                'flush' => true,
            ])->setStatusCode(401);
        }

        return response()->json($this->user($user))->setStatusCode(201);
    }

    protected function user(User $user)
    {
        $user->load('datas');

        return $user;
    }
}
