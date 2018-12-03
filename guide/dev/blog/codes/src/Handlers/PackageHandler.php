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

namespace SlimKit\Plus\Packages\Blog\Handlers;

use Illuminate\Console\Command;

class PackageHandler extends \Zhiyi\Plus\Support\PackageHandler
{
    /**
     * Publish public asstes source handle.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return mixed
     */
    public function publishAsstesHandle(Command $command)
    {
        $force = $command->confirm('Overwrite any existing files');

        return $command->call('vendor:publish', [
            '--provider' => \SlimKit\Plus\Packages\Blog\Providers\AppServiceProvider::class,
            '--tag'      => 'plus-blog-public',
            '--force'    => boolval($force),
        ]);
    }

    /**
     * Publish package config source handle.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return mixed
     */
    public function publishConfigHandle(Command $command)
    {
        $force = $command->confirm('Overwrite any existing files');

        return $command->call('vendor:publish', [
            '--provider' => \SlimKit\Plus\Packages\Blog\Providers\AppServiceProvider::class,
            '--tag'      => 'plus-blog-config',
            '--force'    => boolval($force),
        ]);
    }

    /**
     * Publish package resource handle.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function publishHandle(Command $command)
    {
        return $command->call('vendor:publish', [
            '--provider' => \SlimKit\Plus\Packages\Blog\Providers\AppServiceProvider::class,
        ]);
    }

    /**
     * The migrate handle.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return mixed
     */
    public function migrateHandle(Command $command)
    {
        return $command->call('migrate');
    }

    /**
     * The DB seeder handler.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function dbSeedHandle(Command $command)
    {
        return $command->call('db:seed', [
            '--class' => \SlimKit\Plus\Packages\Blog\Seeds\DatabaseSeeder::class,
        ]);
    }
}
