<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\UserGroup;

class UserGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserGroup::create(['name' => '管理员']);
        UserGroup::create(['name' => '普通用户']);
    }
}
