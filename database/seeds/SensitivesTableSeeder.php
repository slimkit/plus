<?php

use Zhiyi\Plus\Models\Sensitive;
use Illuminate\Database\Seeder;

class SensitivesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sensitive::create(['sensitive' => '操']);
        Sensitive::create(['sensitive' => '操你妈']);
    }
}
