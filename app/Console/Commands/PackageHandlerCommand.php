<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Console\Commands;

use Illuminate\Console\Command;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Zhiyi\Plus\Support\PackageHandler;

class PackageHandlerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'package:handle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run handler of package.';

    /**
     * Run the command.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle()
    {
        $name = $this->argument('name');
        if (! $name) {
            return $this->displayHandles();
        } elseif (! ($handler = $this->getHandles()[$name] ?? false)) {
            throw new RuntimeException('The handler not exist.');
        }

        $handle = $this->argument('handle');
        if (! $handle) {
            return $this->displayMethod($name, $this->resolveHandler($handler));
        }

        return $this->resolveHandler($handler)->handle($this, $handle);
    }

    /**
     * Get package handles.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getHandles(): array
    {
        return PackageHandler::getHandles();
    }

    /**
     * display handle.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function displayHandles()
    {
        foreach ($this->getHandles() as $name => $handler) {
            $this->warn($name);
            $this->displayMethod($name, $this->resolveHandler($handler));
        }
    }

    /**
     * Display handler method.
     *
     * @param string $name
     * @param PackageHandler $handler
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function displayMethod(string $name, PackageHandler $handler)
    {
        $methods = $handler->methods();

        foreach ($methods as $method) {
            $this->info(sprintf('  - %s %s', $name, $method));
        }
    }

    /**
     * Resolve handler.
     *
     * @param string|PackageHandler $handler
     * @return PackageHandler
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveHandler($handler): PackageHandler
    {
        if ($handler instanceof PackageHandler) {
            return $handler;
        }

        return $this->getLaravel()->make($handler);
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
            ['name', InputArgument::OPTIONAL, 'The name of the command.'],
            ['handle', InputArgument::OPTIONAL, 'The name of the handler.'],
        ];
    }
}
