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

namespace SlimKit\PlusQuestion\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Boorstrap the service provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        // Register a database migration path.
        $this->loadMigrationsFrom($this->app->make('path.question.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.question.lang'), 'plus-question');

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.question.resources').'/views', 'plus-question');

        // Register handler singleton.
        $this->registerHandlerSingletions();

        // Publish config file.
        $this->publishes([
            $this->app->make('path.question').'/config/question.php' => $this->app->configPath('question.php'),
        ], 'config');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.question.assets') => $this->app->PublicPath().'/assets/question-answer',
        ], 'public');
    }

    /**
     * Register the service provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        // Bind all of the package paths in the container.
        $this->app->instance('path.question', $path = dirname(dirname(__DIR__)));
        $this->app->instance('path.question.migrations', $path.'/database/migrations');
        $this->app->instance('path.question.assets', $path.'/assets');
        $this->app->instance('path.question.resources', $path.'/resources');
        $this->app->instance('path.question.lang', $path.'/resources/lang');

        // Merge config.
        $this->mergeConfig();

        // register cntainer aliases
        $this->registerContainerAliases();

        // Register Plus package handlers.
        $this->registerPackageHandlers();
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerHandlerSingletions()
    {
        // Owner handler.
        $this->app->singleton('plus-question:handler', function () {
            return new \SlimKit\PlusQuestion\Handlers\PackageHandler();
        });

        // Develop handler.
        $this->app->singleton('plus-question:dev-handler', function ($app) {
            return new \SlimKit\PlusQuestion\Handlers\DevPackageHandler($app);
        });
    }

    /**
     * Register container aliases.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerContainerAliases()
    {
        foreach ([
            'plus-question:handler' => [
                \SlimKit\PlusQuestion\Handlers\PackageHandler::class,
            ],
            'plus-question:dev-handler' => [
                \SlimKit\PlusQuestion\Handlers\DevPackageHandler::class,
            ],
        ] as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerPackageHandlers()
    {
        $this->loadHandleFrom('question', 'plus-question:handler');
        $this->loadHandleFrom('question-dev', 'plus-question:dev-handler');
    }

    /**
     * Register handler.
     *
     * @param string $name
     * @param \Zhiyi\Plus\Support\PackageHandler|string $handler
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function loadHandleFrom(string $name, $handler)
    {
        \Zhiyi\Plus\Support\PackageHandler::loadHandleFrom($name, $handler);
    }

    /**
     * Merge config.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            $this->app->make('path.question').'/config/question.php', 'question'
        );
    }
}
