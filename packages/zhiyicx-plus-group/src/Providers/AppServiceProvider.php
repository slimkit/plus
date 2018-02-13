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

namespace Zhiyi\PlusGroup\Providers;

use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;
use Zhiyi\Plus\Support\PinnedsNotificationEventer;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

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
        $this->loadMigrationsFrom($this->app->make('path.plus-group.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.plus-group.lang'), 'plus-group');

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.plus-group.views'), 'plus-group');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.plus-group.assets') => $this->app->publicPath().'/assets/plus-group',
        ], 'plus-group-public');

        // Publish config.
        $this->publishes([
            $this->app->make('path.plus-group.config').'/plus-group.php' => $this->app->configPath('plus-group.php'),
        ], 'plus-group-config');

        // Register Bootstraper API event.
        $this->app->make(BootstrapAPIsEventer::class)->listen('v2', function () {
            return [
                'group:create' => $this->app->make(ConfigRepository::class)->get('plus-group.group_create'),
                'group:reward' => $this->app->make(ConfigRepository::class)->get('plus-group.group_reward'),
            ];
        });

        // Register PinnedsNotification event.
        $this->app->make(PinnedsNotificationEventer::class)->listen(function () {
            return [
                'name' => 'group-posts',
                'namespace' => \Zhiyi\PlusGroup\Models\Pinned::class,
                'owner_prefix' => 'target_user',
                'wherecolumn' => function ($query) {
                    return $query->where('expires_at', null)->where('channel', 'post')->whereExists(function ($query) {
                        return $query->from('group_posts')->whereRaw('group_pinneds.target = group_posts.id')->where('deleted_at', null);
                    });
                },
            ];
        });

        $this->app->make(PinnedsNotificationEventer::class)->listen(function () {
            return [
                'name' => 'group-comments',
                'namespace' => \Zhiyi\PlusGroup\Models\Pinned::class,
                'owner_prefix' => 'target_user',
                'wherecolumn' => function ($query) {
                    return $query->where('expires_at', null)->where('channel', 'comment')->whereExists(function ($query) {
                        return $query->from('group_posts')->whereRaw('group_pinneds.raw = group_posts.id')->where('deleted_at', null);
                    })->whereExists(function ($query) {
                        return $query->from('comments')->whereRaw('group_pinneds.target = comments.id');
                    });
                },
            ];
        });

        $this
            ->app
            ->make('Illuminate\Database\Eloquent\Factory')
            ->load(__DIR__.'/../../database/factories');
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
            $this->app->make('path.plus-group.config').'/plus-group.php',
            'plus-group'
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
            'path.plus-group' => $root = dirname(dirname(__DIR__)),
            'path.plus-group.assets' => $root.'/assets',
            'path.plus-group.config' => $root.'/config',
            'path.plus-group.database' => $database = $root.'/database',
            'path.plus-group.resources' => $resources = $root.'/resources',
            'path.plus-group.lang' => $resources.'/lang',
            'path.plus-group.views' => $resources.'/views',
            'path.plus-group.migrations' => $database.'/migrations',
            'path.plus-group.seeds' => $database.'/seeds',
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
        // Owner handler.
        $this->app->singleton('plus-group:handler', function () {
            return new \Zhiyi\PlusGroup\Handlers\PackageHandler();
        });

        // Develop handler.
        $this->app->singleton('plus-group:dev-handler', function ($app) {
            return new \Zhiyi\PlusGroup\Handlers\DevPackageHandler($app);
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
            'plus-group:handler' => [
                \Zhiyi\PlusGroup\Handlers\PackageHandler::class,
            ],
            'plus-group:dev-handler' => [
                \Zhiyi\PlusGroup\Handlers\DevPackageHandler::class,
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
        $this->loadHandleFrom('plus-group', 'plus-group:handler');
        $this->loadHandleFrom('plus-group-dev', 'plus-group:dev-handler');
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
