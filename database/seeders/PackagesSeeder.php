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
use SlimKit\PlusPc\Database\Seeders\PcDatabaseSeeder;

class PackagesSeeder extends Seeder
{
    /**
     * Run the seeder in packages.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        $this->call(\SlimKit\PlusCheckIn\Seeds\DatabaseSeeder::class);
        $this->call(\Slimkit\PlusNews\DataBase\Seeders\DatabaseSeeder::class);
        $this->call(\Slimkit\Feed\Database\Seeders\DatabaseSeeder::class);
        $this->call(\SlimKit\PlusMusic\Database\Seeders\DatabaseSeeder::class);
        $this->call(PcDatabaseSeeder::class);
    }
}
