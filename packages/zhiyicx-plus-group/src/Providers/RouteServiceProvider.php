<?php

namespace Zhiyi\PlusGroup\Providers;

use Zhiyi\PlusGroup\Models;
use Illuminate\Support\Facades\Route;
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
            $this->app->make('path.plus-group').'/router.php'
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
        $this->app->make(ManageRepository::class)->loadManageFrom('圈子', 'plus-group:admin-home', [
            'route' => true,
            'icon' => '圈',
        ]);
    }
}
