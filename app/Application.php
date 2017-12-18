<?php

namespace Zhiyi\Plus;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class Application extends LaravelApplication
{
    /**
     * The ThinkSNS Plus version.
     *
     * @var string
     */
    const VERSION = '1.1.0';

    /**
     * The core vendor YAML file.
     *
     * @var string.
     */
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
     * Get the version number of the Laravel framework.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getLaravelVersion()
    {
        return parent::VERSION;
    }

    /**
     * Set load vendor environment yaml file to be loaded during bootstrapping.
     *
     * @param string $file
     * @return $this
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function loadVendorYamlFrom(string $file): ApplicationContract
    {
        $this->vendorYamlFile = $file;

        return $this;
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
     * Register the core class aliases in the container.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function registerCoreContainerAliases()
    {
        parent::registerCoreContainerAliases();

        $aliases = [
            'app' => [static::class],
            'cdn' => [
                \Zhiyi\Plus\Contracts\Cdn\UrlFactory::class,
                \Zhiyi\Plus\Cdn\UrlManager::class,
            ],
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }
}
