<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Providers;

use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(
            $this->app->make('path.test-group-worker').'/router.php'
        );
    }

    /**
     * Regoster the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Publish admin menu.
        $this->app->make(ManageRepository::class)->loadManageFrom('test-group-worker', 'test-group-worker:admin-home', [
            'route' => true,
            'icon' => '📦',
        ]);
    }
}
