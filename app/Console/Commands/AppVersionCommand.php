<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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
use Illuminate\Config\Repository;

class AppVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'app:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application version & packages';

    /**
     * The application Composer repository.
     *
     * @var [type]
     */
    protected $repository;

    /**
     * The console command handle.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle()
    {
        $defaultVersion = $this->getLaravel()->version();
        $version = $this->getOutput()->ask('New version', $defaultVersion);

        $this->initApplicationRepositroy();
        $packages = $this->getPackages();

        foreach ($packages as $package) {
            $package['repository']->set('version', $version);
            $this->repository->set('require.'.$package['repository']->get('name'), $version);
            $this->savePackage($package['path'], $package['repository']);
        }
        $this->setVersionToPackageJson($version);
        $this->savePackage(base_path('composer.json'), $this->repository);
        $this->setVersionToApplicationClass($version);
        $this->comment('Setting new version: '.$version);
    }

    /**
     * Set version to Application::class.
     *
     * @param string $version
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setVersionToApplicationClass(string $version)
    {
        $filename = app_path('Application.php');
        $contents = $this->getFileContents($filename);
        $contents = preg_replace('/const VERSION = \'(.*?)\';/', 'const VERSION = \''.$version.'\';', $contents);
        file_put_contents($filename, $contents);
    }

    protected function setVersionToPackageJson(string $version)
    {
        $repo = $this->createComposerRepositroy('package.json');
        $repo->set('version', $version);
        $this->savePackage(base_path('package.json'), $repo);
    }

    /**
     * Save package [composer.json] file.
     *
     * @param string $filename
     * @param Repository $repository
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function savePackage(string $filename, Repository $repository)
    {
        $contents = json_encode($repository->all(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $contents .= PHP_EOL;
        file_put_contents($filename, $contents);
    }

    /**
     * Get packages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getPackages(): array
    {
        $packages = [];
        foreach ($this->repository->get('repositories') as $repository) {
            if ($repository['type'] !== 'path' || ($repository['options']['plus-soft'] ?? false) === false) {
                continue;
            }

            $packages[] = [
                'path' => base_path($path = $repository['url'].'/composer.json'),
                'repository' => $this->createComposerRepositroy($path),
            ];
        }

        return $packages;
    }

    /**
     * Init the application repositroy.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function initApplicationRepositroy()
    {
        $this->repository = $this->createComposerRepositroy('composer.json');
    }

    /**
     * Create composer repositroy.
     *
     * @param string $path
     * @return Repostroy
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createComposerRepositroy(string $path): Repository
    {
        $path = base_path($path);
        $contents = json_decode($this->getFileContents($path), true);

        return new Repository($contents);
    }

    /**
     * Get file contents.
     *
     * @param string $filename
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getFileContents(string $filename)
    {
        return file_get_contents($filename);
    }
}
