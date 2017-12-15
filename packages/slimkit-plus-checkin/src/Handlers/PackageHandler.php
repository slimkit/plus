<?php

namespace SlimKit\PlusCheckIn\Handlers;

use Illuminate\Console\Command;

class PackageHandler extends \Zhiyi\Plus\Support\PackageHandler
{
    /**
     * Publish public source handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     */
    public function publishPublicHandle(Command $command)
    {
        $force = $command->confirm('Overwrite any existing files');

        return $command->call('vendor:publish', [
            '--provider' => \SlimKit\PlusCheckIn\Providers\AppServiceProvider::class,
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
            '--class' => \SlimKit\PlusCheckIn\Seeds\DatabaseSeeder::class,
        ]);
    }
}
