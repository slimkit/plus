<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\CertificationCategory;

class CertificationCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CertificationCategory::create(['name' => 'user', 'display_name' => '个人认证']);
        CertificationCategory::create(['name' => 'org', 'display_name' => '组织认证']);
    }
}
