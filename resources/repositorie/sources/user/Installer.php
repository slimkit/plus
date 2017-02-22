<?php
namespace Zhiyi\PlusComponentWeb;

use Zhiyi\Component\Installer\PlusInstallPlugin\AbstractInstaller;

class Installer extends AbstractInstaller
{
    public function getComponentInfo()
    {

    }

    public function router()
    {
        return dirname(__FILE__).'/routes.php';
    }

    public function resource()
    {
        return dirname(__FILE__) . '/dist/'; 
    }
}