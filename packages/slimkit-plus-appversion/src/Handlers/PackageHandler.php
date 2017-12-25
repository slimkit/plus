<?php

namespace Slimkit\PlusAppversion\Handlers;

use Illuminate\Console\Command;

class PackageHandler extends \Zhiyi\Plus\Support\PackageHandler
{
    /**
     * Publish public asstes source handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     */
    public function publishAssetsHandle(Command $command)
    {
        $force = $command->confirm('Overwrite any existing files');

        return $command->call('vendor:publish', [
            '--provider' => \Slimkit\PlusAppversion\Providers\AppServiceProvider::class,
            '--tag' => 'public',
            '--force' => boolval($force),
        ]);
    }

    /**
     * The migrate handle.
     *
     * @param \Illuminate\Console\Command $command
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
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function dbSeedHandle(Command $command)
    {
        return $command->call('db:seed', [
            '--class' => \Slimkit\PlusAppversion\Seeds\DatabaseSeeder::class,
        ]);
    }
}
