<?php

namespace Zhiyi\Component\Installer\PlusInstallPlugin;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;

abstract class AbstractInstaller implements InstallerInterface
{
    /**
     * The component:install commmand instance.
     */
    protected $command;

    /**
     * The command output instance.
     */
    protected $output;

    /**
     * 构造方法.
     *
     * @param Illuminate\Console\Command     $command
     * @param Illuminate\Console\OutputStyle $output
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function __construct(Command $command, OutputStyle $output)
    {
        $this->command = $command;
        $this->output = $output;
    }

    /**
     * 路由配置.
     *
     * @return string 路由配置文件列表
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function router()
    {
    }

    /**
     * 静态资源.
     *
     * @return array 静态资源文件列表
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function resource()
    {
    }

    /**
     * 应用安装.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function install(Closure $next)
    {
        $next();
        $this->output->success("Installed the {$this->getName()}");
    }

    /**
     * 应用升级.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function update(Closure $next)
    {
        $next();
        $this->output->success("Updated The {$this->getName()}");
    }

    /**
     * 应用卸载.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function uninstall(Closure $next)
    {
        $next();
        $this->output->success("Uninstall The {$this->getName()}");
    }
}
