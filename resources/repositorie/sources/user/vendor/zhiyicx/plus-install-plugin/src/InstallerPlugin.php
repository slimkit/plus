<?php

namespace Zhiyi\Component\Installer\PlusInstallPlugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class InstallerPlugin implements PluginInterface
{
    protected $type = 'plus-component';

    /**
     * Plugin entrance。
     *
     * @param Composer    $composer
     * @param IOInterface $io
     *
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new PlusComponentInstaller($io, $composer, $this->type);
        $composer
            ->getInstallationManager()
            ->addInstaller($installer);
    }
}
