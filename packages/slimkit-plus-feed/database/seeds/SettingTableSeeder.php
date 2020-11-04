<?php

declare(strict_types=1);

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

namespace Slimkit\Feed\Database\Seeders;

use Illuminate\Database\Seeder;
use function Zhiyi\Plus\setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the package seeder.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        setting('feed')->set([
            'reward-switch' => true,
            'pay-switch' => true,
            'pay-items' => [100, 500, 1000],
            'pay-word-limit' => 50,
        ]);
    }
}
