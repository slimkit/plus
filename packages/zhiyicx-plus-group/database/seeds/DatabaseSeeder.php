<?php

namespace Zhiyi\PlusGroup\Seeds;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 广告位数据
        $this->call(AbilitiesSeeder::class);
    }
}
