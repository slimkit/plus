<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Web\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller as BaseController;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Access as AccessModel;
use Zhiyi\Plus\Packages\TestGroupWorker\Web\Middleware\AssignAccessToken;

class HomeController extends BaseController
{
    /**
     * Create the Controller instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        $this->middleware('auth:web');
        $this->middleware(AssignAccessToken::class);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard(): Guard
    {
        return Auth::guard('api');
    }

    /**
     * The test group worker entry.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user->roles('developer') && ! $user->roles('tester')) {
            abort(403, '您没有权限进入该应用');
        }

        $variables = [
            'accessToken' => $request->session()->get('access_token'),
            'user' => $request->user(),
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ];

        return view('test-group-worker::app', $variables);
    }

    /**
     * Get access model query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getAccessQuery(): Builder
    {
        return AccessModel::query();
    }
}
