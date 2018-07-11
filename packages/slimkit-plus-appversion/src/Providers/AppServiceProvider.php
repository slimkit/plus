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

namespace Slimkit\PlusAppversion\Providers;

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
        $this->loadMigrationsFrom($this->app->make('path.plus-appversion.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.plus-appversion.lang'), 'plus-appversion');

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.plus-appversion.resource').'/views', 'plus-appversion');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.plus-appversion.assets') => $this->app->publicPath().'/assets/appversion',
        ], 'public');

        // Publish config.
        $this->publishes([
            $this->app->make('path.plus-appversion.config').'/plus-appversion.php' => $this->app->configPath('plus-appversion.php'),
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
        $this->app->instance('path.plus-appversion', $path = dirname(dirname(__DIR__)));
        $this->app->instance('path.plus-appversion.database', $path.'/database');
        $this->app->instance('path.plus-appversion.migrations', $path.'/database/migrations');
        $this->app->instance('path.plus-appversion.seeds', $path.'/database/seeds');
        $this->app->instance('path.plus-appversion.assets', $path.'/assets');
        $this->app->instance('path.plus-appversion.resource', $path.'/resource');
        $this->app->instance('path.plus-appversion.lang', $path.'/resource/lang');
        $this->app->instance('path.plus-appversion.config', $configPath = $path.'/config');

        // Merge config.
        $this->mergeConfigFrom($configPath.'/plus-appversion.php', 'plus-appversion');

        // register cntainer aliases
        $this->registerContainerAliases();

        // Register singletons.
        $this->registerSingletions();

        // Register Plus package handlers.
        $this->registerPackageHandlers();
    }

    /**
     * Register singletons.
     *
     * @return void
     */
    protected function registerSingletions()
    {
        // Develop handler.
        $this->app->singleton('plus-appversion:dev-handler', function ($app) {
            return new \Slimkit\PlusAppversion\Handlers\DevPackageHandler($app);
        });
    }

    /**
     * Register container aliases.
     *
     * @return void
     */
    protected function registerContainerAliases()
    {
        foreach ([
            'plus-appversion:dev-handler' => [
                \Slimkit\PlusAppversion\Handlers\DevPackageHandler::class,
            ],
        ] as $abstract => $aliases) {
            foreach ($aliases as $alias) {
                $this->app->alias($abstract, $alias);
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
        $this->loadHandleFrom('plus-appversion-dev', 'plus-appversion:dev-handler');
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
