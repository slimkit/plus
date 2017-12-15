<?php

namespace SlimKit\PlusSocialite\Handlers;

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
            $this->app->make('path.plus-socialite.migrations')
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
