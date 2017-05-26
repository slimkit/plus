<?php

namespace Zhiyi\Plus\Support;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class ProviderRepository
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new service repository instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ApplicationContract $app)
    {
        $this->app = $app;
    }

    /**
     * Register the application service provider.
     *
     * @param array $providers
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function load(array $providers)
    {
        $manifest = $this->compileManifest($providers);

        // Next, we will register events to load the providers for each of the events
        // that it has requested. This allows the service provider to defer itself
        // while still getting automatically loaded when a certain event occurs.
        foreach ($manifest['when'] as $provider => $events) {
            $provider = $manifest['instances'][$provider];
            $this->registerLoadEvents($provider, $events);
        }

        // We will go ahead and register all of the eagerly loaded providers with the
        // application so their services can be registered with the application as
        // a provided service. Then we will set the deferred service list on it.
        foreach ($manifest['eager'] as $provider) {
            $this->app->register($provider);
        }

        $this->app->addDeferredServices($manifest['deferred']);
    }

    /**
     * Register the load events for the given provider.
     *
     * @param \Illuminate\Support\ServiceProvider|string $provider
     * @param array $events
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerLoadEvents($provider, array $events)
    {
        if (count($events) < 1) {
            return;
        }

        $app = $this->app;
        $app->make('events')->listen($events, function () use ($provider, $app) {
            $app->register($provider);
        });
    }

    /**
     * Compile the application service manifest.
     *
     * @param array $providers
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function compileManifest(array $providers): array
    {
        $manifest = [
            'deferred' => [],
            'when' => [],
            'eager' => [],
            'instances' => [],
        ];

        foreach ($providers as $provider) {
            $instance = $this->resolveProvider($provider);
            $provider = is_string($provider) ? $provider : get_class($instance);
            $manifest['instances'][$provider] = $instance;

            if ($instance->isDeferred()) {
                foreach ($instance as $service) {
                    $manifest['deferred'][$service] = $provider;
                }

                $manifest['when'][$provider] = $instance->when();
            } else {
                $manifest['eager'][] = $instance;
            }
        }

        return $manifest;
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param \Illuminate\Support\ServiceProvider|string $provider
     * @return \Illuminate\Support\ServiceProvider
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveProvider($provider): ServiceProvider
    {
        if ($provider instanceof ServiceProvider) {
            return $provider;
        }

        return $this->app->resolveProvider($provider);
    }
}
