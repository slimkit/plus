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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Auth;
use Illuminate\Http\Request;
use Overtrue\Socialite\SocialiteManager;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class SocialiteController extends BaseController
{
    /**
     * 三方登录/绑定（未登录）.
     * @author ZsyD
     * @param Request $request
     * @param string  $service [三方类型]
     * @return mixed
     */
    public function redirectToProvider(Request $request, $service)
    {
        $config[$service] = $this->PlusData['config']['common'][$service];
        $config[$service]['redirect'] = url('/socialite/'.$service.'/callback');

        $socialite = new SocialiteManager($config);

        $response = $socialite->setRequest($request)->driver($service)->redirect();

        $response->send();
    }

    /**
     * 三方登录/绑定（已登录）.
     * @author ZsyD
     * @param  Request $request
     * @param  string  $service [三方类型]
     * @return mixed
     */
    public function redirectToProviderByBind(Request $request, $service)
    {
        if ($this->PlusData['TS']->phone == null) {
            $request->offsetSet('status', 0);
            $request->offsetSet('url', Route('pc:binds'));
            $request->offsetSet('message', '绑定失败');
            $request->offsetSet('content', '绑定第三方账号必须绑定手机号码');

            return $this->notice($request);
        }
        $config[$service] = $this->PlusData['config']['common'][$service];
        $config[$service]['redirect'] = url('/socialite/'.$service.'/callback?type=bind');

        $socialite = new SocialiteManager($config);

        $response = $socialite->setRequest($request)->driver($service)->redirect();

        $response->send();
    }

    /**
     * 第三方回调页.
     * @author ZsyD
     * @param  Request $request
     * @param  string  $service [三方类型]
     * @return mixed
     */
    public function handleProviderCallback(Request $request, $service)
    {
        $config[$service] = $this->PlusData['config']['common'][$service];
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $config[$service]['redirect'] = url('/socialite/'.$service.'/callback'.($type != '' ? '?type=bind' : ''));

        $socialite = new SocialiteManager($config);
        $user = $socialite->setRequest($request)->driver($service)->user();
        $access_token = $user->getToken()->access_token;

        // 已登录时账号绑定
        if ($type == 'bind') {
            $res = api('PATCH', '/api/v2/user/socialite/'.$service, ['access_token' => $access_token]);

            $request->offsetSet('status', isset($res['message']) ? 0 : 1);
            $request->offsetSet('url', Route('pc:binds'));
            $request->offsetSet('message', isset($res['message']) ? '绑定失败' : '绑定成功');
            $request->offsetSet('content', isset($res['message']) ? $res['message'] : '您的账号已成功绑定');

            return $this->notice($request);
        } else {
            // 未登录时账号注册/绑定
            $res = api('POST', '/api/v2/socialite/'.$service, ['access_token' => $access_token]);

            if (isset($res['token'])) { // 登录
                $jwt = app(\Tymon\JWTAuth\JWTAuth::class);
                $jwt->setToken($res['token']);
                $jwt->authenticate();
                $user = $jwt->user();
                Auth::login($user);

                $return = [
                    'status' => 1,
                    'message' => '登录成功',
                ];
            } else { // 绑定、注册
                $return = [
                    'status' => -1,
                    'message' => '正在前往绑定窗口...',
                    'data' => [
                        'other_type' => $service,
                        'access_token' => $access_token,
                        'name' => $user->getName(),
                    ],
                ];
            }

            return view('pcview::socialite.socialite', $return, $this->PlusData);
        }
    }

    /**
     * 三方用户注册/绑定账号（未登录时）.
     * @author ZsyD
     * @param  Request $request
     * @return mixed
     */
    public function bind(Request $request)
    {
        $data = $request->input();

        return view('pcview::socialite.bind', $data, $this->PlusData);
    }

    /**
     * 获取登录信息.
     * @author ZsyD<1251992018@qq.com>
     * @param $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function getToken($token)
    {
        $jwt = app(\Tymon\JWTAuth\JWTAuth::class);
        $jwt->setToken($token);
        $jwt->authenticate();
        $user = $jwt->user();
        Auth::login($user);

        return redirect(route('pc:feeds'));
    }
}
