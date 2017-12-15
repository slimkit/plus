<?php

namespace SlimKit\PlusSocialite\Providers;

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
            $this->app->make('path.plus-socialite').'/router.php'
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
        // $this->app->make(ManageRepository::class)->loadManageFrom('plus-socialite', 'plus-socialite:admin-home', [
        //     'route' => true,
        //     'icon' => '📦',
        // ]);
    }
}
