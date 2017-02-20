<?php

namespace Zhiyi\Plus\Console\Commands;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Zhiyi\Component\Installer\PlusInstallPlugin\InstallerInterface;

class ComponentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'component';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installed the app component.';

    /**
     * The console command filesystem.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->getNameInput();
        $component = $this->getComponentInput();

        $installer = $this->getInstallerInstance($component);

        // install call.
        $install = $this->getClosureBind($this, function () use ($installer, $component) {
            $this->installVendorComponentRouter($installer, $component);
            $this->installVendorComponentResource($installer, $component);
            $this->changeInstalledStatus($component, true);
        });

        // update call.
        $update = clone $install;

        // uninstall call.
        $uninstall = $this->getClosureBind($this, function () use ($component) {
            $this->removeVendorComponentRouter($component);
            $this->removeVendorComponentResource($component);
            $this->changeInstalledStatus($component, false);
        });

        $installer->$name($$name);
    }

    /**
     * Change component installed status.
     *
     * @param string $componentName component name
     * @param bool   $status        status
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function changeInstalledStatus(string $componentName, bool $status)
    {
        $settings = config('component');
        $settings[$componentName]['installed'] = $status;

        $this->filePutIterator(config_path('component.php'), $settings);
    }

    /**
     * Bind The Closure.
     *
     * @param mixed   $bind
     * @param Closure $call
     *
     * @return Closure
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function getClosureBind($bind, Closure $call): Closure
    {
        $call->bindTo($bind);

        return $call;
    }

    /**
     * Get the component installer instance.
     *
     * @param string $componentName
     *
     * @return InstallerInterface
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function getInstallerInstance(string $componentName): InstallerInterface
    {
        $installConfig = array_get(config('component'), $componentName);
        if (!$installConfig) {
            throw new \Exception("The {$componentName} not require.");
        }

        $installer = new $installConfig['installer']($this, $this->output);
        if (!$installer instanceof InstallerInterface) {
            throw new \Exception(sprintf('The %s not implement %s', $componentName, InstallerInterface::class));
        }

        return $installer;
    }

    /**
     * Remove the component resource.
     *
     * @param string $componentName
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function removeVendorComponentResource(string $componentName)
    {
        $this->filesystem->deleteDirectory(public_path($componentName));
        $this->info("Deleted the {$componentName} resource successfully.");
    }

    /**
     * Remove The component router.
     *
     * @param string $componentName
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function removeVendorComponentRouter(string $componentName)
    {
        $routes = config('component_routes');
        unset($routes[$componentName]);
        $this->filePutIterator(config_path('component_routes.php'), $routes);
        $this->info("Deleted the {$componentName} router successfully.");
    }

    /**
     * Installed resource entry.
     *
     * @param InstallerInterface $installer
     * @param string             $componentName
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function installVendorComponentResource(InstallerInterface $installer, string $componentName)
    {
        $resource = $installer->resource();
        if ($resource) {
            $this->doInstallVendorComponentResource($componentName, $resource);
        }
    }

    /**
     * Do run install resource.
     *
     * @param string $componentName
     * @param string $resource
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function doInstallVendorComponentResource(string $componentName, string $resource)
    {
        if (!$this->filesystem->isDirectory($resource)) {
            throw new \Exception("Directory desc not exist as path {$resource}");
        }

        $this->filesystem->deleteDirectory(public_path($componentName));
        $status = $this->filesystem->copyDirectory($resource, public_path($componentName));
        if ($status === false) {
            throw new \Exception("Copy the {$componentName} resource failed.");
        }
        $this->info("Copy the {$componentName} resource successfully.");
    }

    /**
     * install router entry.
     *
     * @param InstallerInterface $installer
     * @param string             $componentName
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function installVendorComponentRouter(InstallerInterface $installer, string $componentName)
    {
        $router = $installer->router();
        if ($router) {
            $this->doInstallVendorComponentRouter($componentName, $router);
        }
    }

    /**
     * Do run install router.
     *
     * @param string $componentName
     * @param string $filename
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function doInstallVendorComponentRouter(string $componentName, string $filename)
    {
        if (!$this->filesystem->exists($filename)) {
            throw new \Exception("File does not exist at path {$filename}");
        }

        $routes = config('component_routes');
        $routes[$componentName] = $filename;

        $this->filePutIterator(config_path('component_routes.php'), $routes);
        $this->info("The {$componentName} created router successfully.");
    }

    /**
     * Save php file by interator.
     *
     * @param string $filename
     * @param array  $datas
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function filePutIterator(string $filename, array $datas)
    {
        $data = '<?php '.PHP_EOL;
        $data .= 'return '.PHP_EOL;
        $data .= var_export($datas, true);
        $data .= ';'.PHP_EOL;

        $this->filesystem->put($filename, $data);
    }

    /**
     * get "component" argument.
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function getComponentInput()
    {
        if (!$this->hasArgument('component')) {
            throw new \Exception('"component" already exists!');
        }

        return $this->argument('component');
    }

    /**
     * get "name" argument.
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function getNameInput()
    {
        $name = strtolower($this->argument('name'));
        if (!in_array($name, ['install', 'update', 'uninstall'])) {
            throw new \Exception('The name of [install, update, uninstall].');
        }

        return $name;
    }

    /**
     * get command arguments.
     *
     * @return array
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The command of [install, update, uninstall]'],
            ['component', InputArgument::REQUIRED, 'The name of the component.'],
        ];
    }
}
