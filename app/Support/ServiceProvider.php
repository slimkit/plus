<?php

namespace Zhiyi\Plus\Support;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * Push manage url.
     *
     * @param string $name
     * @param string $uri
     * @param array $option
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function loadManageFrom(string $name, string $uri, array $option = [])
    {
        $this->app->make(ManageRepository::class)->loadManageFrom($name, $uri, $option);
    }

    /**
     * Register handler.
     *
     * @param string $name
     * @param PackageHandler $handler
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function loadHandleFrom(string $name, PackageHandler $handler)
    {
        PackageHandler::loadHandleFrom($name, $handler);
    }

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
