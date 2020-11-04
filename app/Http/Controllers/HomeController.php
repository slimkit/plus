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

namespace Zhiyi\Plus\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class HomeController
{
    /**
     * Home page.
     *
     * @param \Jenssegers\Agent\Agent $agent
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function welcome(Agent $agent)
    {
        // If request client is mobile and opened SPA.
        if ($agent->isMobile() && config('http.spa.open')) {
            return redirect(config('http.spa.url'));

        // If web is opened.
        } elseif (config('http.web.open')) {
            return redirect(config('http.web.url'));
        }

        // By default, view welcome page.
        return view('welcome');
    }

    /**
     * Redirect URL.
     * @param \Illuminate\Http\Request $request
     * @param \Jenssegers\Agent\Agent $agent
     * @return mixed
     */
    public function redirect(Request $request, Agent $agent)
    {
        $target = $request->input('target', '');
        $isUrl = filter_var($target, FILTER_VALIDATE_URL, FILTER_VALIDATE_IP);
        $redirect = '';
        if ($isUrl !== false) {
            $redirect = $isUrl;

            return redirect($redirect);
        }

        $agent->isMobile();
        $config = config('http');
        $web = $config['web'];
        $spa = $config['spa'];
        if ($agent->isMobile() && $spa['open']) {
            $redirect = trim($spa['url'], '/').$target;

            return redirect($redirect);
        }
        if ($web['open']) {
            $redirect = trim(config('app.url'), '/').$target;

            return redirect($redirect);
        }

        return view('welcome');
    }
}
