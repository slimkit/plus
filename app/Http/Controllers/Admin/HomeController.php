<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Admin home.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request)
    {
        $this->abortIfAuthenticated($user = $request->user());

        $data = [
            'csrf_token' => csrf_token(),
            'base_url'   => url('admin'),
            'api'        => url('api/v1'),
            'logged'     => (bool) $user,
            'user'       => $user,
            'token' => JWTAuth::fromUser($user),
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
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function abortIfAuthenticated($user)
    {
        if ($user && ! $user->can('admin:login')) {
            abort(401, '你没有权限访问该页面');
        }
    }
}
