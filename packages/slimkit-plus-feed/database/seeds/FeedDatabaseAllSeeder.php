<?php

use Illuminate\Database\Seeder;

class FeedDatabaseAllSeeder extends Seeder
{
    /**
     * Run seeder.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        $this->call(FeedAbilitySeeder::class);
        $this->call(FeedAdvertisingSpaceSeeder::class);
    }
}
