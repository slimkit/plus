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

namespace SlimKit\PlusQuestion\Handlers;

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
     * @author Seven Du <shiweidu@outlook.com>
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
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createMigrationHandle(Command $command)
    {
        // Resolve migration file path.
        $path = $this->pathRelative(
            $this->app->basePath(),
            $this->app->make('path.question.migrations')
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

    /**
     * Path relative.
     *
     * @param string $fromPath
     * @param string $toPath
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function pathRelative(string $fromPath, string $toPath): string
    {
        $fromPath = str_replace('\\', '/', realpath($fromPath));
        $toPath = str_replace('\\', '/', realpath($toPath));

        $fromParts = explode('/', $fromPath);
        $toParts = explode('/', $toPath);

        $length = min(count($fromParts), count($toParts));
        $samePartsLength = $length;
        for ($i = 0; $i < $length; $i++) {
            if ($fromParts[$i] !== $toParts[$i]) {
                $samePartsLength = $i;
                break;
            }
        }

        $outputParts = [];
        for ($i = $samePartsLength; $i < count($fromParts); $i++) {
            array_push($outputParts, '..');
        }

        $outputParts = array_merge($outputParts, array_slice($toParts, $samePartsLength) ?: []);

        return implode('/', $outputParts);
    }
}
