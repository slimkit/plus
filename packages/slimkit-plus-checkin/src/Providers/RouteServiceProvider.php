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

namespace SlimKit\PlusCheckIn\Providers;

use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

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
            $this->app->make('path.checkin').'/router.php'
        );

        // Register Bootstraper API event.
        $this->app->make(BootstrapAPIsEventer::class)->listen('v2', function () {
            return [
                'checkin' => (bool) $this->app->make(ConfigRepository::class)->get('checkin.open'),
                'checkin:attach_balance' => (int) $this->app->make(ConfigRepository::class)->get('checkin.attach_balance'),
            ];
        });

        // Register manage menu.
        $this->registerManageMenu();
    }

    /**
     * Register manage menu.
     *
     * @return void
     */
    public function registerManageMenu()
    {
        // Publish admin menu.
        $this->app->make(ManageRepository::class)->loadManageFrom(trans('plus-checkin::app.name'), 'checkin:admin-home', [
            'route' => true,
            'icon' => asset('assets/checkin/icon.svg'),
        ]);
    }
}
