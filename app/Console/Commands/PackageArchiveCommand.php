<?php

namespace Zhiyi\Plus\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\PhpExecutableFinder;

class PackageArchiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'package:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an archive of this composer package.';

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
        $package = $this->getPackageInput();
        $dir = $this->getSourceDir($package);

        if (($version = $this->argument('version')) !== null) {
            $process = $this->getProcess($dir);
            $process->setCommandLine($this->findComposer($dir).' config version '.$version);
            $process->run();
        }

        $process = $this->getProcess($dir);
        $process->setCommandLine($this->findComposer($dir).' archive --format=zip --dir='.$this->getZipDir());
        $process->run();

        $this->info('Creating the archive successfully.');
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer($workingPath)
    {
        $phpScript = ProcessUtils::escapeArgument(
            app(PhpExecutableFinder::class)->find(false)
        );

        if ($this->filesystem->exists($workingPath.'/composer.phar')) {
            return sprintf('%s composer.phar', $phpScript);
        }

        if ($this->filesystem->exists(getcwd().'/composer.phar')) {
            return sprintf('%s %s/composer.phar', $phpScript, getcwd());
        }

        return 'composer';
    }

    /**
     * Get a new Symfony process instance.
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess(string $workingPath)
    {
        return (new Process('', $workingPath))->setTimeout(null);
    }

    protected function getZipDir()
    {
        return resource_path('repositorie/zips/');
    }

    protected function getSourceDir(string $package)
    {
        $sourceDir = resource_path('repositorie/sources/'.$package);

        if (! $this->filesystem->exists($sourceDir.'/composer.json')) {
            throw new \Exception('The package is not "composer.json" exist!');
        }

        return $sourceDir;
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
            throw new \Exception('"package" already exists!');
        }

        return str_replace('/', '-', $this->argument('package'));
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
            ['package', InputArgument::REQUIRED, 'The package to archive instead by dir.'],
            ['version', InputArgument::OPTIONAL, 'Constrain a version of this package'],
        ];
    }
}
