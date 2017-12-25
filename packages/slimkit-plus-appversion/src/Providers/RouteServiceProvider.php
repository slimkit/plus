<?php

namespace Slimkit\PlusAppversion\Providers;

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
            $this->app->make('path.plus-appversion').'/router.php'
        );
        $this->app->make(BootstrapAPIsEventer::class)->listen('v2', function () {
            return [
                'plus-appversion' => [
                    'open' => (bool) $this->app->make(ConfigRepository::class)->get('plus-appversion.open')
                ]
            ];
        });
    }

    /**
     * Regoster the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Publish admin menu.
        $this->app->make(ManageRepository::class)->loadManageFrom('App版本控制', 'plus-appversion:admin-home', [
            'route' => true,
            'icon' => '版',
        ]);
    }
}
