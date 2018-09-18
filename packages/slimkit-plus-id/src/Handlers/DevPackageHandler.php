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

namespace SlimKit\PlusID\Handlers;

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
    public function makeMigrationHandle(Command $command)
    {
        // Resolve migration file path.
        $path = Path::relative(
            $this->app->basePath(),
            $this->app->make('path.plus-id.migrations')
        );

        // Ask table name.
        $table = $command->getOutput()->ask('Enter the table name', null, function ($table) {
            if (! preg_match('/^[a-z0-9_]+$/is', $table)) {
                throw new \InvalidArgumentException(
                    'The name '.$table.' is invalid, matching: [a-z0-9_]'
                );
            }

            return $table;
        });

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
     * Create a database seeder.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function makeSeederHandle(Command $command)
    {
        $path = $this->app->make('path.plus-id.seeds');
        $name = $command->getOutput()->ask('Enter the seeder name', null, function ($name) {
            if (! preg_match('/^[a-z][a-z0-9_-]+[a-z0-9]$/is', $name)) {
                throw new \InvalidArgumentException(
                    'The name '.$name.' is invalid, matching: [a-z][a-z0-9_-]+[a-z0-9]'
                );
            }

            return $name;
        });
        $name = ucfirst(camel_case($name));
        $filename = $path.'/'.$name.'Seeder.php';

        if (file_exists($filename)) {
            throw new \InvalidArgumentException('The file ['.$filename.'] already exists.');
        }

        $content = file_get_contents(
            $this->app->make('path.plus-id.resources').'/stubs/seeder'
        );
        $content = str_replace('{--name--}', $name, $content);

        file_put_contents($filename, $content);
        $command->info(sprintf('Create this "%s" seeder successfully.', $filename));
    }

    /**
     * Make package model.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function makeModelHandle(Command $command)
    {
        $modelName = $command->getOutput()->ask('Enter the Model name', null, function ($modelName) {
            if (! preg_match('/^[a-zA-Z0-9]+$/is', $modelName)) {
                throw new \InvalidArgumentException(
                    'The name '.$modelName.' is invalid, matching: [a-zA-Z0-9]'
                );
            }

            return $modelName;
        });
        $modelName = ucfirst(camel_case($modelName));
        $table = str_plural(strtolower(snake_case($modelName)));
        $table = $command->getOutput()->ask('Enter the table name', $table, function ($table) {
            if (! preg_match('/^[a-z0-9_]+$/is', $table)) {
                throw new \InvalidArgumentException(
                    'The name '.$table.' is invalid, matching: [a-z0-9_]'
                );
            }

            return $table;
        });

        $makeMigration = $command->confirm('Create the migration of this model');
        $filename = dirname(__DIR__).'/Models/'.$modelName.'.php';

        if (file_exists($filename)) {
            throw new \InvalidArgumentException('The file ['.$filename.'] already exists.');
        }

        $content = file_get_contents(
            $this->app->make('path.plus-id.resources').'/stubs/model'
        );
        $variable = [
            '{--name--}' => $modelName,
            '{--table-name--}' => $table,
        ];
        foreach ($variable as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        file_put_contents($filename, $content);

        if ($makeMigration) {
            // Resolve migration file path.
            $path = Path::relative(
                $this->app->basePath(),
                $this->app->make('path.plus-id.migrations')
            );

            return $command->call('make:migration', [
                'name' => sprintf('create_%s_table', $table),
                '--path' => $path,
                '--table' => $table,
                '--create' => true,
            ]);
        }
    }
}
