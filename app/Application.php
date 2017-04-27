<?php

namespace Zhiyi\Plus;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Foundation\Application as LaravelApplication;

class Application extends LaravelApplication
{
    /**
     * Register all of the configured providers.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function registerConfiguredProviders()
    {
        (new ProviderRepository($this, new Filesystem, $this->getCachedServicesPath()))
                    ->load($this->getConfiguredProviders());
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function registerCoreContainerAliases()
    {
        parent::registerCoreContainerAliases();

        // Register class aliases.
        $this->alias('app', \Zhiyi\Plus\Application::class);
    }

    /**
     * Get all of the configured providers.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getConfiguredProviders(): array
    {
        return array_merge(
            $this->config['app.providers'],
            $this->config['providers'] ?? []
        );
    }
}
