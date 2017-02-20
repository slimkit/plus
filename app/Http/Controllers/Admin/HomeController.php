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
            'menus'      => $this->menus(),
        ];

        return view('admin', $data);
    }

    protected function menus()
    {
        $components = config('component');
        $menus = [];

        foreach ($components as $component => $info) {
            $info = (array) $info;
            $installer = array_get($info, 'installer');
            $installed = array_get($info, 'installed', false);
            
            if (!$installed || !$installer) {
                continue;
            }

            $componentInfo = (new $installer)->getComponentInfo();

            if (!$componentInfo) {
                continue;
            }

            $menus[$component] = [
                'name' => $componentInfo->getName(),
                'icon' => $componentInfo->getIcon(),
                'logo' => $componentInfo->getLogo(),
                'admin' => $componentInfo->getAdminEntry(),
            ];

        }

        return $menus;
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

    protected function menuModel()
    {
        return (new Menu())->newQuery();
    }
}
