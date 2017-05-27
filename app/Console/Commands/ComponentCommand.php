<?php

namespace Zhiyi\Plus\Console\Commands;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Zhiyi\Component\Installer\PlusInstallPlugin\InstallerInterface;
use Zhiyi\Component\Installer\PlusInstallPlugin\ComponentInfoInterface;

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
        $this->$name($installer, $component);

        $this->error(
            'This command is about to be removed, and this development will not be supported.'.PHP_EOL.
            'We designed a better way to develop please refer to:'.PHP_EOL.
            '    https://github.com/zhiyicx/plus-installer'
        );

        $this->openBrowser('https://github.com/zhiyicx/plus-installer');
    }

    /**
     * opens a url in your system default browser.
     *
     * @param string $url
     */
    private function openBrowser($url)
    {
        $url = ProcessUtils::escapeArgument($url);

        if (windows_os()) {
            $process = new Process('start "web" explorer "'.$url.'"');

            return $process->run();
        }

        $process = new Process('which xdg-open');
        $linux = $process->run();

        $process = new Process('which open');
        $osx = $process->run();

        if ($linux === 0) {
            $process = new Process('xdg-open '.$url);

            return $process->run();
        } elseif ($osx === 0) {
            $process = new Process('open '.$url);

            return $process->run();
        }
    }

    /**
     * 卸载步骤.
     *
     * @param InstallerInterface $installer
     * @param string $component
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function uninstall(InstallerInterface $installer, string $component)
    {
        $installer->uninstall(
            $this->getClosureBind($this, function () use ($component) {
                $this->removeVendorComponentRouter($component);
                $this->removeVendorComponentResource($component);
                $this->changeInstalledStatus($component, false);
            })
        );
    }

    /**
     * 升级步骤.
     *
     * @param InstallerInterface $installer
     * @param string $component component name.
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function update(InstallerInterface $installer, string $component)
    {
        $installer->update(
            $this->getClosureBind($this, function () use ($installer, $component) {
                $this->installVendorComponentRouter($installer, $component);
                $this->installVendorComponentResource($installer, $component);
                $this->changeInstalledStatus($component, true);
            })
        );
    }

    /**
     * 安装步骤.
     *
     * @param InstallerInterface $installer
     * @param string $component component name.
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function install(InstallerInterface $installer, string $component)
    {
        $installer->install(
            $this->getClosureBind($this, function () use ($installer, $component) {
                $this->installVendorComponentRouter($installer, $component);
                $this->installVendorComponentResource($installer, $component);
                $this->changeInstalledStatus($component, true);
            })
        );
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
    public function getClosureBind($bind, Closure $call): Closure
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
        if (! $installConfig) {
            throw new \Exception("The {$componentName} not require.");
        }

        $installer = new $installConfig['installer']();
        if (! $installer instanceof InstallerInterface) {
            throw new \Exception(sprintf('The %s not implement %s', $componentName, InstallerInterface::class));
        }

        $componentInfo = $installer->getComponentInfo();
        if ($componentInfo && ! ($componentInfo instanceof ComponentInfoInterface)) {
            throw new \Exception(printf('The getComponentInfo() return object not implement %s', ComponentInfoInterface::class));
        }

        $installer->setCommand($this, $this->output);

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
        $dir = public_path($componentName);
        if (! $this->filesystem->delete($dir)) {
            $this->filesystem->deleteDirectory($dir);
        }

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
        if (! $resource) {
            return;
        }

        if (! $this->filesystem->isDirectory($resource)) {
            throw new \Exception("Directory desc not exist as path {$resource}");
        }

        if ($this->copyVendorComponentResource($componentName, $resource) === false) {
            throw new \Exception("Copy the {$componentName} resource failed.");
        }

        $this->info("Copy the {$componentName} resource successfully.");
    }

    /**
     * Copy the component resource.
     *
     * @param string $componentName
     * @param string $resource
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function copyVendorComponentResource(string $componentName, string $resource): bool
    {
        $link = public_path($componentName);

        // Deleted old directory.
        $this->removeVendorComponentResource($componentName, $resource);

        if ($this->isLink()) {
            return $this->createResourceLink($resource, $link);
        }

        return $this->filesystem->copyDirectory($resource, $link);
    }

    /**
     * Create component resource link.
     *
     * @param string $target
     * @param string $link
     * @return true
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createResourceLink(string $target, string $link): bool
    {
        $parentDir = dirname($link);

        // Parent dir is existe.
        if (! $this->filesystem->isDirectory($parentDir)) {
            $this->filesystem->makeDirectory($parentDir);
        }

        // delete link dir.
        if (! $this->filesystem->delete($link)) {
            $this->filesystem->deleteDirectory($link, false);
        }

        // create link.
        $this->filesystem->link($target, $link);

        return true;
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
        if (! $router) {
            return;
        }

        if (! $this->filesystem->exists($router)) {
            throw new \Exception("File does not exist at path {$router}");
        }

        $routes = config('component_routes');
        $routes[$componentName] = $router;

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
    public function filePutIterator(string $filename, array $datas)
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
        if (! $this->hasArgument('component')) {
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
        if (! in_array($name, ['install', 'update', 'uninstall'])) {
            throw new \Exception('The name of [install, update, uninstall].');
        }

        return $name;
    }

    /**
     * Is link resource.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function isLink(): bool
    {
        return (bool) $this->option('link');
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

    /**
     * Get the console command options.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getOptions()
    {
        return [
            ['link', null, InputOption::VALUE_NONE, 'Use a soft link to point to the component\'s resource, only install and update are valid.'],
        ];
    }
}
