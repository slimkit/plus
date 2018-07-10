<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace SlimKit\PlusSocialite\Providers;

use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(
            $this->app->make('path.plus-socialite').'/router.php'
        );
    }

    /**
     * Regoster the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Publish admin menu.
        // $this->app->make(ManageRepository::class)->loadManageFrom('plus-socialite', 'plus-socialite:admin-home', [
        //     'route' => true,
        //     'icon' => '📦',
        // ]);
    }
}
