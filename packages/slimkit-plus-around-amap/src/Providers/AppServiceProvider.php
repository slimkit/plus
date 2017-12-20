<?php

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

namespace SlimKit\PlusAroundAmap\Providers;

use Zhiyi\Plus\Support\PackageHandler;
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

        // Register Plus package handlers.
        $this->registerPackageHandlers();
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     */
    protected function registerHandlerSingletions()
    {
        // Owner handler.
        $this->app->singleton('plus-around-amap:handler', function () {
            return new \SlimKit\PlusAroundAmap\Handlers\PackageHandler();
        });

        // Develop handler.
        $this->app->singleton('plus-around-amap:dev-handler', function ($app) {
            return new \SlimKit\PlusAroundAmap\Handlers\DevPackageHandler($app);
        });
    }

    /**
     * Register container aliases.
     *
     * @return void
     */
    protected function registerContainerAliases()
    {
        $aliases = [
            'plus-around-amap:handler' => [
                \SlimKit\PlusAroundAmap\Handlers\PackageHandler::class,
            ],
            'plus-around-amap:dev-handler' => [
                \SlimKit\PlusAroundAmap\Handlers\DevPackageHandler::class,
            ],
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ($aliases as $key => $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     */
    protected function registerPackageHandlers()
    {
        $this->loadHandleFrom('around-amap', 'plus-around-amap:handler');
        $this->loadHandleFrom('around-amap-dev', 'plus-around-amap:dev-handler');
    }

    /**
     * Register handler.
     *
     * @param string $name
     * @param \Zhiyi\Plus\Support\PackageHandler|string $handler
     * @return void
     */
    private function loadHandleFrom(string $name, $handler)
    {
        PackageHandler::loadHandleFrom($name, $handler);
    }
}
