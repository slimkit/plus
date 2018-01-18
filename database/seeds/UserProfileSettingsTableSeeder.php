<?php

/*
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
use Zhiyi\Plus\Models\UserProfileSetting;

class UserProfileSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            // base.
            ['create_user_id' => '1', 'profile' => 'sex', 'profile_name' => '性别', 'type' => 'radio', 'required' => '1', 'is_delable' => '0', 'state' => '1', 'default_options' => '1:男|2:女|3:其他', 'is_show' => '1'],
            ['create_user_id' => '1', 'profile' => 'location', 'profile_name' => '地区', 'type' => 'multiselect', 'required' => '1', 'is_delable' => '0', 'state' => '1', 'is_show' => '1'],
            ['create_user_id' => '1', 'profile' => 'intro', 'profile_name' => '简介', 'type' => 'textarea', 'required' => '1', 'is_delable' => '0', 'state' => '1', 'is_show' => '1'],
            ['create_user_id' => '1', 'profile' => 'province', 'profile_name' => '省', 'type' => 'input', 'required' => '1', 'is_delable' => '0', 'state' => '1', 'is_show' => '0'],
            ['create_user_id' => '1', 'profile' => 'city', 'profile_name' => '市', 'type' => 'input', 'required' => '1', 'is_delable' => '0', 'state' => '1', 'is_show' => '0'],
            ['create_user_id' => '1', 'profile' => 'area', 'profile_name' => '区', 'type' => 'input', 'required' => '1', 'is_delable' => '0', 'state' => '1', 'is_show' => '0'],
            ['create_user_id' => '1', 'profile' => 'education', 'profile_name' => '学历', 'type' => 'checkbox', 'required' => 1, 'is_delable' => '0', 'state' => '1', 'default_options' => '1:高中|2:大专|3:本科', 'is_show' => '1'],
            ['create_user_id' => '1', 'profile' => 'name', 'profile_name' => '昵称', 'type' => 'input', 'required' => '1', 'is_delable' => '0', 'state' => '1', 'is_show' => '1'],
            [
                'create_user_id' => '1',
                'profile'        => 'cover',
                'profile_name'   => '个人主页背景图',
                'type'           => 'input',
                'required'       => '1',
                'is_delable'     => '0',
                'state'          => '1',
                'is_show'        => '1',
            ],
            // 用户头像
            [
                'create_user_id' => 0,
                'profile'        => 'avatar',
                'profile_name'   => '用户头像',
                'type'           => 'input',
                'required'       => 0,
                'is_delable'     => 0,
                'state'          => 1,
                'is_show'        => 0,
                // 'default_options' => ''
            ],
        ];

        foreach ($datas as $data) {
            UserProfileSetting::create($data);
        }
    }
}
