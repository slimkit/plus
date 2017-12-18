<?php

use Illuminate\Database\Seeder;

class PackagesSeeder extends Seeder
{
    /**
     * Run the seeder in packages.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        $this->call(\SlimKit\PlusCheckIn\Seeds\DatabaseSeeder::class);
        $this->call(\SlimKit\Plus\Packages\News\Seeds\DatabaseSeeder::class);
        $this->Call(\SlimKit\Plus\Packages\Feed\Seeds\DatabaseSeeder::class);
    }
}
