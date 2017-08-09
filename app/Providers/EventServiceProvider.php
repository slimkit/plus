<?php

namespace Zhiyi\Plus\Providers;

use Illuminate\Support\Facades\Event;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Illuminate\Notifications\Events\NotificationSent::class => [
            \Zhiyi\Plus\Listeners\VerificationCode::class,
        ],
    ];

    /**
     * Register the provider service.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        // Run parent register method.
        parent::register();

        // Register BootstrapAPIsEventer event singleton.
        $this->app->singleton(BootstrapAPIsEventer::class, function ($app) {
            return new BootstrapAPIsEventer(
                $app->make(EventsDispatcherContract::class)
            );
        });
    }
}
