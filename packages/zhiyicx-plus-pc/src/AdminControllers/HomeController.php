<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Zhiyi\Plus\Auth\JWTAuthToken;
use Zhiyi\Plus\Http\Controllers\Controller;

class HomeController extends Controller
{
    use AuthenticatesUsers {
        login as traitLogin;
    }

    /**
     * pc后台首页.
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function show(Request $request, JWTAuthToken $jwt)
    {
        if (! $request->user()) {
            return redirect(route('admin'), 302);
        }

        config('jwt.single_auth', false);

        return view('pcview::admin', [
            'token' => $jwt->create($request->user()),
            'base_url' => route('pc:admin'),
            'csrf_token' => csrf_token(),
            'api' => url('api/v2'),
            'files' => url('/api/v2/files'),
        ]);
    }

    /**
     * 菜单.
     * @author 28youth
     * @return array
     */
    protected function menus()
    {
        $components = config('component');
        $menus = [];

        foreach ($components as $component => $info) {
            $info = (array) $info;
            $installer = Arr::get($info, 'installer');
            $installed = Arr::get($info, 'installed', false);

            if (! $installed || ! $installer) {
                continue;
            }

            $componentInfo = app($installer)->getComponentInfo();

            if (! $componentInfo) {
                continue;
            }

            $menus[$component] = [
                'name'  => $componentInfo->getName(),
                'icon'  => $componentInfo->getIcon(),
                'logo'  => $componentInfo->getLogo(),
                'admin' => $componentInfo->getAdminEntry(),
            ];
        }

        return $menus;
    }
}
