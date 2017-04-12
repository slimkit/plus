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

        // auth:api -> token provider.
        Auth::provider('token', function ($app) {
            return $app->make(TokenUserProvider::class);
        });

        // auth:api -> token guard.
        Auth::extend('token', function ($app, $name, array $config) {
            if ($name === 'api') {
                return $app->make(TokenGuard::class, [
                    'provider' => Auth::createUserProvider($config['provider']),
                    'request' => $app->request,
                ]);
            }
        });
    }
}
