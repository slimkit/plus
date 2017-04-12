<?php

namespace Zhiyi\Plus\Providers;

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
        $this->app->auth->provider('token', function ($app) {
            return $app->make(TokenUserProvider::class);
        });

        // auth:api -> token guard.
        // @throw \Exception
        $this->app->auth->extend('token', function ($app, $name, array $config) {
            if ($name === 'api') {
                return $app->make(TokenGuard::class, [
                    'provider' => $app->auth->createUserProvider($config['provider']),
                    'request' => $app->request,
                ]);
            }

            throw new \Exception('This guard only serves "auth:api".');
        });
    }
}
