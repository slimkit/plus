<?php

namespace SlimKit\PlusQuestion\Providers;

use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        $this->loadRoutesFrom(
            $this->app->make('path.question').'/router.php'
        );

        // Publish admin menu.
        $this->app->make(ManageRepository::class)->loadManageFrom('问答应用', 'plus-question::admin', [
            'route' => true,
            'icon' => '问',
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        // Register Bootstraper API event.
        $this->app->make(BootstrapAPIsEventer::class)->listen('v2', function () {
            return [
                'question:apply_amount'     => (int) config('question.apply_amount'),
                'question:onlookers_amount' => (int) config('question.onlookers_amount')
            ];
        });
    }
}
