<?php

namespace Zhiyi\Plus\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Zhiyi\Plus\Services\Auth\TokenGuard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Zhiyi\Plus\Model' => 'Zhiyi\Plus\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->app->auth->extend('token', function ($app) {
            return $app->make(TokenGuard::class);
        });
    }
}
