<?php

namespace Zhiyi\Plus;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Foundation\Application as LaravelApplication;

class Application extends LaravelApplication
{
    protected $vendorEnvironmentYamlFile;

    /**
     * Create a new Illuminate application instance.
     *
     * @param string|null $basePath
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        // Load configuration after.
        $this->afterBootstrapping(\Illuminate\Foundation\Bootstrap\LoadConfiguration::class, function ($app) {
            $app->make(\Zhiyi\Plus\Bootstrap\LoadConfiguration::class)
                ->handle();
        });
    }

    /**
     * Set load vendor environment yaml file to be loaded during bootstrapping.
     *
     * @param string $file
     * @return $this
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function loadVendorEnvironmentYamlFrom(string $file): self
    {
        $this->vendorEnvironmentYamlFile = $file;
    }

    /**
     * Get the environment yaml file the application using.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function vendorEnvironmentYamlFile(): string
    {
        return $this->vendorEnvironmentYamlFile ?: '.plus.yml';
    }

    /**
     * Get the fully qualified path to the environment yaml file.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function vendorEnvironmentYamlFilePath(): string
    {
        return $this->environmentPath().DIRECTORY_SEPARATOR.$this->vendorEnvironmentYamlFile();
    }

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
