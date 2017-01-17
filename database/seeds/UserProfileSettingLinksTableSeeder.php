<?php

use App\Model\UserProfileSettingLink;
use Illuminate\Database\Seeder;

class UserProfileSettingLinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 1, 'user_profile_setting_data' => '1']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 2, 'user_profile_setting_data' => '四川省 成都市 武侯区']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 3, 'user_profile_setting_data' => '我是大管理员']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 4, 'user_profile_setting_data' => '510000']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 5, 'user_profile_setting_data' => '510100']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 6, 'user_profile_setting_data' => '510107']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 7, 'user_profile_setting_data' => '3']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 8, 'user_profile_setting_data' => '管理员']);
    }
}
