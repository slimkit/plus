<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Sensitive;

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
