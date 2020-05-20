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

namespace SlimKit\PlusCheckIn\Providers;

use Illuminate\Support\ServiceProvider;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;
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
            $this->app->make('path.checkin').'/router.php'
        );

        // Register Bootstraper API event.
        $this->app->make(BootstrapAPIsEventer::class)->listen('v2', function () {
            return [
                'checkin' => [
                    'switch' => setting('checkin', 'switch', true),
                    'balance' => setting('checkin', 'attach-balance', 1),
                ],
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
