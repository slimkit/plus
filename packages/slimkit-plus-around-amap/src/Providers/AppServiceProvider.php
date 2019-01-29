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

namespace SlimKit\PlusAroundAmap\Providers;

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
        $this->loadMigrationsFrom($this->app->make('path.around-amap.migration'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.around-amap.lang'), 'plus-around-amap');

        // Register handler singleton.
        $this->registerHandlerSingletions();

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.around-amap.assets') => $this->app->PublicPath().'/assets/around-amap',
        ], 'public');

        $this->loadViewsFrom($this->app->make('path.around-amap.view'), 'around-amap');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind all of the package paths in the container.
        $this->app->instance('path.around-amap', $path = dirname(dirname(__DIR__)));
        $this->app->instance('path.around-amap.migration', $path.'/database/migrations');
        $this->app->instance('path.around-amap.assets', $path.'/assets');
        $this->app->instance('path.around-amap.lang', $path.'/resource/lang');
        $this->app->instance('path.around-amap.view', $path.'/resource/views');

        // register cntainer aliases
        $this->registerContainerAliases();
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     */
    protected function registerHandlerSingletions()
    {
    }

    /**
     * Register container aliases.
     *
     * @return void
     */
    protected function registerContainerAliases()
    {
        $aliases = [];

        foreach ($aliases as $key => $aliases) {
            foreach ($aliases as $key => $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }
}
