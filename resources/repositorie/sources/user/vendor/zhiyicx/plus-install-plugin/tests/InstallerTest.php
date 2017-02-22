<?php

namespace Zhiyi\Component\Installer\PlusInstallPlugin\Test;

use Composer\Composer;
use Composer\Config;
use Composer\Util\Filesystem;
use Zhiyi\Component\Installer\PlusInstallPlugin\PlusComponentInstaller;

class InstallerTest extends TestCase
{
    protected $composer;
    protected $config;
    protected $rootDir;
    protected $vendorDir;
    protected $binDir;
    protected $dm;
    protected $repository;
    protected $io;
    protected $fs;

    protected function setUp()
    {
        $this->fs = new Filesystem();

        $this->composer = new Composer();
        $this->config = new Config();
        $this->composer->setConfig($this->config);

        $this->rootDir = $this->getUniqueTmpDirectory();
        $this->ensureDirectoryExistsAndClear($this->rootDir);

        // use dir.
        chdir($this->rootDir);

        $this->vendorDir = $this->rootDir.DIRECTORY_SEPARATOR.'vendor';
        $this->ensureDirectoryExistsAndClear($this->vendorDir);

        $this->binDir = $this->rootDir.DIRECTORY_SEPARATOR.'bin';
        $this->ensureDirectoryExistsAndClear($this->binDir);

        $this->config->merge([
            'config' => [
                'vendor-dir' => $this->vendorDir,
                'bin-dir'    => $this->binDir,
            ],
        ]);

        $this->dm = $this->getMockBuilder('Composer\Downloader\DownloadManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->composer->setDownloadManager($this->dm);

        $this->repository = $this->createMock('Composer\Repository\InstalledRepositoryInterface');
        $this->io = $this->createMock('Composer\IO\IOInterface');
    }

    protected function tearDown()
    {
        $this->fs->removeDirectory($this->rootDir);
    }

    public function testInstall()
    {
        $library = new PlusComponentInstaller($this->io, $this->composer);
        $package = $this->createPackageMock();

        $package
            ->expects($this->any())
            ->method('getPrettyName')
            ->will($this->returnValue('some/package'));

        $package
            ->expects($this->any())
            ->method('getExtra')
            ->will($this->returnValue([
                'installer-class' => 'demo',
            ]));

        $this->dm
            ->expects($this->once())
            ->method('download')
            ->with($package, $this->vendorDir.'/some/package');

        $this->repository
            ->expects($this->once())
            ->method('addPackage')
            ->with($package);

        $library->install($this->repository, $package);
        $this->assertFileExists($this->vendorDir, 'Vendor dir should be created');
        $this->assertFileExists($this->binDir, 'Bin dir should be created');
    }

    protected function createPackageMock()
    {
        return $this->getMockBuilder('Composer\Package\Package')
            ->setConstructorArgs([md5(mt_rand()), '1.0.0.0', '1.0.0'])
            ->getMock();
    }
}
