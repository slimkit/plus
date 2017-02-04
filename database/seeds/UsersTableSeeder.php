<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => '管理员', 'phone' => '18781993582', 'password' => bcrypt('123456')]);
        User::create(['name' => 'Wayne', 'phone' => '18908019700', 'password' => bcrypt('123456')]);
    }
}
