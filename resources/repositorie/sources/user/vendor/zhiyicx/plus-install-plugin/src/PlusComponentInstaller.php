<?php

namespace Zhiyi\Component\Installer\PlusInstallPlugin;

use Composer\Composer;
use Composer\Installer\BinaryInstaller;
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem as FilesystemUtil;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;

class PlusComponentInstaller extends LibraryInstaller
{
    protected $fs;

    /**
     * Initializes library installer.
     *
     * @param IOInterface     $io
     * @param Composer        $composer
     * @param string          $type
     * @param Filesystem      $filesystem
     * @param BinaryInstaller $binaryInstaller
     */
    public function __construct(IOInterface $io, Composer $composer, $type = 'library', FilesystemUtil $filesystem = null, BinaryInstaller $binaryInstaller = null)
    {
        parent::__construct($io, $composer, $type, $filesystem, $binaryInstaller);
        $this->fs = new Filesystem();
    }

    /**
     * supports package type.
     *
     * @param string $packageType
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function supports($packageType): bool
    {
        $isPlusComponent = $packageType === 'plus-component';

        return $isPlusComponent;
    }

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $componentName = $package->getPrettyName();
        $installerClass = $this->getPackageInstallerClass($package);

        // run installer.
        parent::install($repo, $package);
        $this->updateInstallClasses($componentName, $installerClass);
    }

    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        $componentName = $target->getPrettyName();
        $installerClass = $this->getPackageInstallerClass($target);

        parent::update($repo, $initial, $target);
        $this->updateInstallClasses($componentName, $installerClass);
    }

    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::uninstall($repo, $package);
        $this->removeComponentInstaller($package->getPrettyName());
    }

    protected function getPackageInstallerClass(PackageInterface $package): string
    {
        $extra = $package->getExtra();
        if (!$extra['installer-class']) {
            throw new InvalidArgumentException(
                sprintf(
                    'The %s component is not set "installer-class" field.'.PHP_EOL
                    .'Using the following config within your package composer.json will allow this:'.PHP_EOL
                    .'{'.PHP_EOL
                    .'    "type": "plus-component",'.PHP_EOL
                    .'    "extra": {'.PHP_EOL
                    .'        "installer-class": "Vendor\\\\Name\\\\Component\\\\Installer"'.PHP_EOL
                    .'    }'.PHP_EOL
                    .'}'.PHP_EOL,
                    $package->getName()
                )
            );
        }

        return $extra['installer-class'];
    }

    protected function getComponentConfigFile()
    {
        return getcwd().'/config/component.php';
    }

    protected function removeComponentInstaller($componentName)
    {
        $configFile = $this->getComponentConfigFile();
        $settings = $this->parentConfig($configFile);

        if (isset($settings[$componentName])) {
            unset($settings[$componentName]);
        }

        $this->filePutIterator($configFile, $settings);
    }

    protected function updateInstallClasses(string $componentName, string $installerClass)
    {
        try {
            $settings = [$componentName => $installerClass];

            $configFile = $this->getComponentConfigFile();
            $settings = array_merge($this->parentConfig($configFile), $settings);

            $this->filePutIterator($configFile, $settings);
        } catch (\Exception $e) {
            parent::uninstall($repo, $package);
            throw $e;
        }
    }

    protected function parentConfig(string $filename): array
    {
        if (!$this->fs->exists($filename)) {
            $this->filePutIterator($filename, []);
        }

        $settings = include $filename;

        return (array) $settings;
    }

    protected function filePutIterator(string $filename, array $datas)
    {
        $string = '<?php '.PHP_EOL.'return ';
        $string .= var_export($datas, true);
        $string .= ';'.PHP_EOL;

        $this->fs->dumpFile($filename, $string);
    }
}
