<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\UserGroupLink;

class UserGroupLinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserGroupLink::create(['user_id' => 1, 'user_group_id' => 1]);
        UserGroupLink::create(['user_id' => 2, 'user_group_id' => 1]);
        UserGroupLink::create(['user_id' => 1, 'user_group_id' => 2]);
        UserGroupLink::create(['user_id' => 2, 'user_group_id' => 2]);
    }
}
