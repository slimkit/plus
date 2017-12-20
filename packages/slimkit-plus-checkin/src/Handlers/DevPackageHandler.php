<?php

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

namespace SlimKit\PlusCheckIn\Handlers;

use Zhiyi\Plus\Utils\Path;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class DevPackageHandler extends \Zhiyi\Plus\Support\PackageHandler
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * create the devleop package handler.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(ApplicationContract $app)
    {
        $this->app = $app;
    }

    /**
     * Create a migration file.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     */
    public function createMigrationHandle(Command $command)
    {
        // Resolve migration file path.
        $path = Path::relative(
            $this->app->basePath(),
            $this->app->make('path.checkin.migrations')
        );

        // Ask table name.
        $table = $command->ask('Enter the table name');

        // Ask migration file prefix.
        $prefix = $command->ask('Enter the table migration prefix', 'create');

        // Ask the migration a new creation
        $create = $command->confirm('The migration a new creation');

        return $command->call('make:migration', [
            'name' => sprintf('%s_%s_table', $prefix, $table),
            '--path' => $path,
            '--table' => $table,
            '--create' => $create,
        ]);
    }
}
