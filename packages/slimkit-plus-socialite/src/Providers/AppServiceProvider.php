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

        $this->app->singleton(SocialiteManager::class, function ($app) {
            return new SocialiteManager($app);
        });
    }
}
