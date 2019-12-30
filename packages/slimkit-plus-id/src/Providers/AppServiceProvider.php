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

namespace SlimKit\PlusID\Providers;

use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\PackageHandler;

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
        $this->loadMigrationsFrom($this->app->make('path.plus-id.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.plus-id.lang'), 'plus-id');

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.plus-id.views'), 'plus-id');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.plus-id.assets') => $this->app->publicPath().'/assets/plus-id',
        ], 'plus-id-public');

        // Publish config.
        $this->publishes([
            $this->app->make('path.plus-id.config').'/plus-id.php' => $this->app->configPath('plus-id.php'),
        ], 'plus-id-config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind all of the package paths in the container.
        $this->bindPathsInContainer();

        // Merge config.
        $this->mergeConfigFrom(
            $this->app->make('path.plus-id.config').'/plus-id.php',
            'plus-id'
        );

        // register cntainer aliases
        $this->registerCoreContainerAliases();

        // Register singletons.
        $this->registerSingletions();

        // Register Plus package handlers.
        $this->registerPackageHandlers();
    }

    /**
     * Bind paths in container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        foreach ([
            'path.plus-id' => $root = dirname(dirname(__DIR__)),
            'path.plus-id.assets' => $root.'/assets',
            'path.plus-id.config' => $root.'/config',
            'path.plus-id.database' => $database = $root.'/database',
            'path.plus-id.resources' => $resources = $root.'/resources',
            'path.plus-id.lang' => $resources.'/lang',
            'path.plus-id.views' => $resources.'/views',
            'path.plus-id.migrations' => $database.'/migrations',
            'path.plus-id.seeds' => $database.'/seeds',
        ] as $abstract => $instance) {
            $this->app->instance($abstract, $instance);
        }
    }

    /**
     * Register singletons.
     *
     * @return void
     */
    protected function registerSingletions()
    {
        // Develop handler.
        $this->app->singleton('plus-id:dev-handler', function ($app) {
            return new \SlimKit\PlusID\Handlers\DevPackageHandler($app);
        });
    }

    /**
     * Register the package class aliases in the container.
     *
     * @return void
     */
    protected function registerCoreContainerAliases()
    {
        foreach ([
            'plus-id:dev-handler' => [
                \SlimKit\PlusID\Handlers\DevPackageHandler::class,
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
        $this->loadHandleFrom('plus-id-dev', 'plus-id:dev-handler');
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
