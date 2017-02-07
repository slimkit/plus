<?php

namespace Zhiyi\Plus\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use stdClass;
use Illuminate\Filesystem\Filesystem;

class ComponentInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:install';

    protected $name = 'component:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installed the app component.';

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
        $component = $this->getNameInput();

        $installer = array_get(config('component'), $component);

        if (!$installer) {
            throw new \Exception("The {$component} not require.");
        }
        
        if (is_string($installer)) {
            $installer = new $installer($this);
        }

        $installer->install();
        $this->installVendorComponentRouter($installer, $component);
        $this->installVendorComponentResource($installer, $component);
    }

    protected function installVendorComponentResource($installer, string $componentName)
    {
        $resource = $installer->resource();
        if ($resource) {
            $this->doInstallVendorComponentResource($componentName, $resource);
        }
    }

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

    protected function installVendorComponentRouter($installer, string $componentName)
    {
        $router = $installer->router();
        if ($router) {
            $this->doInstallVendorComponentRouter($componentName, $router);
        }
    }

    protected function doInstallVendorComponentRouter(string $componentName, string $filename)
    {
        if (!$this->filesystem->exists($filename)) {
            throw new \Exception("File does not exist at path {$filename}");
        }

        $routes = config('component_routes');
        $routes[$componentName] = $filename;

        $data = '<?php '.PHP_EOL;
        $data .= 'return ';
        $data .= var_export($routes, true);
        $data .=';'.PHP_EOL;

        $this->filesystem->put(config_path('component_routes.php'), $data);
        $this->info("The {$componentName} created router successfully.");
    }

    protected function getNameInput()
    {
        if (!$this->hasArgument('name')) {
            $this->error('name already exists!');
        }

        return $this->argument('name');
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of The component.'],
        ];
    }
}
