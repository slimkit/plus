<?php

namespace Zhiyi\Plus\Console\Commands;

use RuntimeException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PackagePublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'package:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish any publishable assets from package';

    /**
     * Run the command.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle()
    {
        $providers = $this
            ->getLaravel()
            ->getProvider(\Zhiyi\Plus\Installer\InstallerServiceProvider::class)
            ->getProviders();

        if (! $provider = $providers[$package = $this->getPackageInput()] ?? false) {
            throw new RuntimeException(sprintf('This "%s" package is not installed', $package));
        }
        
        $this->call(
            'vendor:publish',
            [
                '--force' => $this->option('force'),
                '--provider' => $provider,
                '--tag' => $this->option('tag'),
            ]
        );
    }

    /**
     * Get the command [package].
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function getPackageInput()
    {
        if (! $this->hasArgument('package')) {
            throw new RuntimeException('"package" already exists!');
        }

        return $this->argument('package');
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
            ['package', InputArgument::REQUIRED, 'The service provider that has assets you want to publish.'],
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
            ['force', null, InputOption::VALUE_NONE, 'Overwrite any existing files.'],
            ['tag', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'One or many tags that have assets you want to publish.'],
        ];
    }
}
