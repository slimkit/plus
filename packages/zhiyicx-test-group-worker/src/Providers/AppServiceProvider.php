<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Providers;

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
        $this->loadMigrationsFrom($this->app->make('path.test-group-worker.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.test-group-worker.lang'), 'test-group-worker');

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.test-group-worker.views'), 'test-group-worker');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.test-group-worker.assets') => $this->app->publicPath().'/assets/test-group-worker',
        ], 'test-group-worker-public');

        // Publish config.
        $this->publishes([
            $this->app->make('path.test-group-worker.config').'/test-group-worker.php' => $this->app->configPath('test-group-worker.php'),
        ], 'test-group-worker-config');
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
            $this->app->make('path.test-group-worker.config').'/test-group-worker.php',
            'test-group-worker'
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
            'path.test-group-worker' => $root = dirname(dirname(__DIR__)),
            'path.test-group-worker.assets' => $root.'/assets',
            'path.test-group-worker.config' => $root.'/config',
            'path.test-group-worker.database' => $database = $root.'/database',
            'path.test-group-worker.resources' => $resources = $root.'/resources',
            'path.test-group-worker.lang' => $resources.'/lang',
            'path.test-group-worker.views' => $resources.'/views',
            'path.test-group-worker.migrations' => $database.'/migrations',
            'path.test-group-worker.seeds' => $database.'/seeds',
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
        $this->app->singleton('test-group-worker:handler', function () {
            return new \Zhiyi\Plus\Packages\TestGroupWorker\Handlers\PackageHandler();
        });

        // Develop handler.
        $this->app->singleton('test-group-worker:dev-handler', function ($app) {
            return new \Zhiyi\Plus\Packages\TestGroupWorker\Handlers\DevPackageHandler($app);
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
            'test-group-worker:handler' => [
                \Zhiyi\Plus\Packages\TestGroupWorker\Handlers\PackageHandler::class,
            ],
            'test-group-worker:dev-handler' => [
                \Zhiyi\Plus\Packages\TestGroupWorker\Handlers\DevPackageHandler::class,
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
        $this->loadHandleFrom('test-group-worker', 'test-group-worker:handler');
        $this->loadHandleFrom('test-group-worker-dev', 'test-group-worker:dev-handler');
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
