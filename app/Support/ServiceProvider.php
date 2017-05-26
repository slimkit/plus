<?php

namespace Zhiyi\Plus\Support;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * Load service provider.
     *
     * @param array|string $provider
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function loadProvider($providers)
    {
        if (is_string($providers)) {
            $providers = [$providers];
        }

        $this->app->make(ProviderRepository::class)->load($providers);
    }
}
