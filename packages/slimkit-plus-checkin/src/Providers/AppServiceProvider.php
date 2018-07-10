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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Boorstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        // Register a database migration path.
        $this->loadMigrationsFrom($this->app->make('path.checkin.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.checkin.lang'), 'plus-checkin');

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.checkin.view'), 'plus-checkin');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.checkin.assets') => $this->app->publicPath().'/assets/checkin',
        ], 'public');

        // Publish config.
        $this->publishes([
            $this->app->make('path.checkin.config') => $this->app->configPath('checkin.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind all of the package paths in the container.
        $this->app->instance('path.checkin', $path = dirname(dirname(__DIR__)));
        $this->app->instance('path.checkin.migrations', $path.'/database/migrations');
        $this->app->instance('path.checkin.assets', $path.'/assets');
        $this->app->instance('path.checkin.config', $configFilename = $path.'/config/checkin.php');
        $this->app->instance('path.checkin.lang', $path.'/resource/lang');
        $this->app->instance('path.checkin.view', $path.'/resource/views');

        // Merge config.
        $this->mergeConfigFrom($configFilename, 'checkin');
    }
}
