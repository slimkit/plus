<?php

/*
 * declare(strict_types=1);
 *
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\UserProfileSettingLink;

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
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 2, 'user_profile_setting_data' => '北京市 市辖区 东城区']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 3, 'user_profile_setting_data' => '我是大管理员']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 4, 'user_profile_setting_data' => '110000']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 5, 'user_profile_setting_data' => '110100']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 6, 'user_profile_setting_data' => '110101']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 7, 'user_profile_setting_data' => '3']);
        UserProfileSettingLink::create(['user_id' => '1', 'user_profile_setting_id' => 8, 'user_profile_setting_data' => '管理员']);
    }
}
