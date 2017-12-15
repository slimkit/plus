<?php

namespace SlimKit\Plus\Packages\News\Seeds;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the package seeder.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        $this->call(AbilityTableSeeder::class);
        $this->call(AdvertisingSpaceTableSeeder::class);
    }
}
