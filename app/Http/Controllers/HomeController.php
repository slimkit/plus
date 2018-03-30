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

namespace Zhiyi\Plus\Http\Controllers;

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
}
