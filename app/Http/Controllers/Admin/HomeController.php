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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Support\ManageRepository;

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
        $user = $request->user();
        $data = [
            'csrf_token' => csrf_token(),
            'base_url'   => url('admin'),
            'api'        => url('api/v2'),
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
}
