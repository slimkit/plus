<?php

use Illuminate\Database\Seeder;

class PcDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PcTableSeeder::class);
    }
}
