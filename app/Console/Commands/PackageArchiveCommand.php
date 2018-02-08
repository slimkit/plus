<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

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
     * Escapes a string to be used as a shell argument.
     *
     * @param string $argument
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function escapeArgument(string $argument): string
    {
        return forward_static_call([ProcessUtils::class, 'escapeArgument'], $argument);
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer($workingPath)
    {
        $includeArgs = false;
        $phpScript = $this->escapeArgument(
            (new PhpExecutableFinder)->find($includeArgs)
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
