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

namespace SlimKit\Plus\Packages\Blog\Providers;

use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\PackageHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        // Register a database migration path.
        $this->loadMigrationsFrom($this->app->make('path.plus-blog.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.plus-blog.lang'), 'plus-blog');

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.plus-blog.views'), 'plus-blog');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.plus-blog.assets') => $this->app->publicPath().'/assets/plus-blog',
        ], 'plus-blog-public');

        // Publish config.
        $this->publishes([
            $this->app->make('path.plus-blog.config').'/plus-blog.php' => $this->app->configPath('plus-blog.php'),
        ], 'plus-blog-config');
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
            $this->app->make('path.plus-blog.config').'/plus-blog.php',
            'plus-blog'
        );

        // register cntainer aliases
        $this->registerCoreContainerAliases();

        // Register singletons.
        $this->registerSingletons();

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
            'path.plus-blog' => $root = dirname(dirname(__DIR__)),
            'path.plus-blog.assets' => $root.'/assets',
            'path.plus-blog.config' => $root.'/config',
            'path.plus-blog.database' => $database = $root.'/database',
            'path.plus-blog.resources' => $resources = $root.'/resources',
            'path.plus-blog.lang' => $resources.'/lang',
            'path.plus-blog.views' => $resources.'/views',
            'path.plus-blog.migrations' => $database.'/migrations',
            'path.plus-blog.seeds' => $database.'/seeds',
        ] as $abstract => $instance) {
            $this->app->instance($abstract, $instance);
        }
    }

    /**
     * Register singletons.
     *
     * @return void
     */
    protected function registerSingletons()
    {
        // Owner handler.
        $this->app->singleton('plus-blog:handler', function () {
            return new \SlimKit\Plus\Packages\Blog\Handlers\PackageHandler();
        });

        // Develop handler.
        $this->app->singleton('plus-blog:dev-handler', function ($app) {
            return new \SlimKit\Plus\Packages\Blog\Handlers\DevPackageHandler($app);
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
            'plus-blog:handler' => [
                \SlimKit\Plus\Packages\Blog\Handlers\PackageHandler::class,
            ],
            'plus-blog:dev-handler' => [
                \SlimKit\Plus\Packages\Blog\Handlers\DevPackageHandler::class,
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
        $this->loadHandleFrom('plus-blog', 'plus-blog:handler');
        $this->loadHandleFrom('plus-blog-dev', 'plus-blog:dev-handler');
    }

    /**
     * Register handler.
     *
     * @param string                                    $name
     * @param \Zhiyi\Plus\Support\PackageHandler|string $handler
     *
     * @return void
     */
    private function loadHandleFrom(string $name, $handler)
    {
        PackageHandler::loadHandleFrom($name, $handler);
    }
}
