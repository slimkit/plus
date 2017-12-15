<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed;

use Zhiyi\Plus\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Support\PackageHandler;

class FeedPackageHandler extends PackageHandler
{
    /**
     * Resolve handler.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function resolveHandle($command)
    {
        // publish public assets
        $command->call('vendor:publish', [
            '--provider' => FeedServiceProvider::class,
            '--tag' => 'public',
            '--force' => true,
        ]);

        // Run the database migrations
        $command->call('migrate');

        if ($command->confirm('Run seeder')) {
            // Run the database seeds.
            $command->call('db:seed', [
                '--class' => \FeedDatabaseAllSeeder::class,
            ]);
        }
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
        $path = str_replace(app('path.base'), '', dirname(__DIR__).'/database/migrations');
        $table = $command->ask('Enter the table name');
        $prefix = $command->ask('Enter the table migration prefix', 'create');
        $name = sprintf('%s_%s_table', $prefix, $table);
        $create = $command->confirm('Is it creating a new migration');

        return $command->call('make:migration', [
            'name' => $name,
            '--path' => $path,
            '--table' => $table,
            '--create' => $create,
        ]);
    }
}
