<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Providers;

use Zhiyi\Plus\AppInterface;
use Zhiyi\Plus\FileStorage\Storage;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\FileStorage\ChannelManager;
use Zhiyi\Plus\FileStorage\StorageInterface;
use Zhiyi\Plus\FileStorage\FilesystemManager;
use Zhiyi\Plus\FileStorage\Http\MakeRoutes;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register StorageInterface instance.
        $this->app->singleton(StorageInterface::class, function (AppInterface $app) {
            $manager = $this->app->make(ChannelManager::class);

            return new Storage($app, $manager);
        });

        // // Register FilesystemManager instance.
        // $this->app->singleton(FilesystemManager::class, function (AppInterface $app) {
        //     return new FilesystemManager($app);
        // });

        // // Register ChannelManager instance.
        // $this->app->singleton(ChannelManager::class, function (AppInterface $app) {
        //     $manager = $this->app->make(FilesystemManager::class);

        //     return new ChannelManager($app, $manager);
        // });
    }

    public function boot()
    {
        $this
            ->app
            ->make(MakeRoutes::class)
            ->register();
    }
}
