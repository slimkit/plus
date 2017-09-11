<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\GoldType;

class GoldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = GoldType::where('name', '积分')->count();
        if (! $count) {
            GoldType::create(['name' => '积分', 'unit' => '点', 'status' => 1]);
        }
    }
}
