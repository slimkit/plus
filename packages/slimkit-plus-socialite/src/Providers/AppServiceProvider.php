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

namespace SlimKit\PlusSocialite\Providers;

use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;
use SlimKit\PlusSocialite\SocialiteManager;

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
        $this->loadMigrationsFrom($this->app->make('path.plus-socialite.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.plus-socialite.lang'), 'plus-socialite');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.plus-socialite.assets') => $this->app->publicPath().'/assets/socialite',
        ], 'public');

        // Publish config.
        $this->publishes([
            $this->app->make('path.plus-socialite.config') => $this->app->configPath('socialite.php'),
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
        $this->app->instance('path.plus-socialite', $path = dirname(dirname(__DIR__)));
        $this->app->instance('path.plus-socialite.migrations', $path.'/database/migrations');
        $this->app->instance('path.plus-socialite.assets', $path.'/assets');
        $this->app->instance('path.plus-socialite.config', $configFilename = $path.'/config/socialite.php');
        $this->app->instance('path.plus-socialite.lang', $path.'/resource/lang');

        // Merge config.
        $this->mergeConfigFrom($configFilename, 'socialite');

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
        // Owner handler.
        $this->app->singleton('plus-socialite:handler', function () {
            return new \SlimKit\PlusSocialite\Handlers\PackageHandler();
        });

        // Develop handler.
        $this->app->singleton('plus-socialite:dev-handler', function ($app) {
            return new \SlimKit\PlusSocialite\Handlers\DevPackageHandler($app);
        });

        // Socialite manager.
        $this->app->singleton(SocialiteManager::class, function ($app) {
            return new SocialiteManager($app);
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
            'plus-socialite:handler' => [
                \SlimKit\PlusSocialite\Handlers\PackageHandler::class,
            ],
            'plus-socialite:dev-handler' => [
                \SlimKit\PlusSocialite\Handlers\DevPackageHandler::class,
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
        $this->loadHandleFrom('plus-socialite', 'plus-socialite:handler');
        $this->loadHandleFrom('plus-socialite-dev', 'plus-socialite:dev-handler');
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
