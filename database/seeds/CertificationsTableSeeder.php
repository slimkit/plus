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
        Certification::create(['name' => 'personal_certification', 'display_name' => '个人认证', 'desc' => '个人认证']);
        Certification::create(['name' => 'enterprise_certification', 'display_name' => '企业认证', 'desc' => '企业认证']);
    }
}
