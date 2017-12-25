<?php

namespace Zhiyi\Plus\Packages\Music\Seeds;

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
        $this->call(AbilitiesTableSeeder::class);
    }
}
