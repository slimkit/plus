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
        // Check in package
        $this->call(\SlimKit\PlusCheckIn\Seeds\DatabaseSeeder::class);
    }
}
