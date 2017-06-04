<?php

namespace Zhiyi\Plus;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Foundation\Application as LaravelApplication;

class Application extends LaravelApplication
{
    protected $vendorYamlFile;

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
    public function loadVendorYamlFrom(string $file): self
    {
        $this->vendorYamlFile = $file;
    }

    /**
     * Get the environment yaml file the application using.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function vendorYamlFile(): string
    {
        return $this->vendorYamlFile ?: '.plus.yml';
    }

    /**
     * Get the fully qualified path to the environment yaml file.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function vendorYamlFilePath(): string
    {
        return $this->environmentPath().DIRECTORY_SEPARATOR.$this->vendorYamlFile();
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
     * Get the path to the cached packages.php file.
     *
     * @return string
     */
    public function getCachedPackagesPath()
    {
        return $this->bootstrapPath('cache/packages.php');
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
            $this->config['providers'] ?? [], // 内部转换完成后废弃。
            $this->make(PackageManifest::class)->providers(),
            $this->config['app.providers']
        );
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerBaseBindings()
    {
        parent::registerBaseBindings();
        $this->instance(PackageManifest::class, new PackageManifest(
            new Filesystem, $this->basePath(), $this->getCachedPackagesPath()
        ));
    }
}
