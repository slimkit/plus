<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CertificationCategoryTableSeeder::class); // 用户认证类型
        $this->call(AdvertisingSpaceTableSeeder::class); // 广告位类型
        $this->call(PackagesSeeder::class); // Packages seeder.
        $this->call(CurrencyTypeSeeder::class); // 默认的货币类型
        $this->call(TagsTableSeeder::class); // 标签和标签分类
        $this->call(SettingsTableSeeder::class); // 设置表
        // 把地区放在最后，因为耗时较长.
        $this->call(AreasTableSeeder::class);
    }
}
