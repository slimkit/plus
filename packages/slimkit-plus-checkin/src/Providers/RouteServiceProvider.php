<?php

namespace SlimKit\PlusCheckIn\Providers;

use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

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
            $this->app->make('path.checkin').'/router.php'
        );

        // Register Bootstraper API event.
        $this->app->make(BootstrapAPIsEventer::class)->listen('v2', function () {
            return [
                'checkin' => (bool) $this->app->make(ConfigRepository::class)->get('checkin.open'),
                'checkin:attach_balance' => (int) $this->app->make(ConfigRepository::class)->get('checkin.attach_balance'),
            ];
        });

        // Register manage menu.
        $this->registerManageMenu();
    }

    /**
     * Register manage menu.
     *
     * @return void
     */
    public function registerManageMenu()
    {
        // Publish admin menu.
        $this->app->make(ManageRepository::class)->loadManageFrom(trans('plus-checkin::app.name'), 'checkin:admin-home', [
            'route' => true,
            'icon' => asset('assets/checkin/icon.svg'),
        ]);
    }
}
