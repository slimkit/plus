<?php

use Zhiyi\Plus\Models\UserGroup;
use Illuminate\Database\Seeder;

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
