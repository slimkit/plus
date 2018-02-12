<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

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
