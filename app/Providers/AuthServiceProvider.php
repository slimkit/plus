<?php

namespace Zhiyi\Plus\Providers;

use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Services\Auth\TokenGuard;
use Zhiyi\Plus\Services\Auth\TokenUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        Auth::extend('token', function ($app, $name, array $config) {
            return new TokenGuard(Auth::createUserProvider($config['provider']), $app->request);
        });
        Auth::provider('token', function ($app, array $config) {
            return new TokenUserProvider();
        });
    }
}
