<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Certification;

class CertificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Certification::create(['name' => 'user', 'display_name' => '个人认证']);
        Certification::create(['name' => 'org', 'display_name' => '组织认证']);
    }
}
