<?php declare(strict_types=1);

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

namespace SlimKit\Plus\Packages\News\Seeds;

use Zhiyi\Plus\Models\Ability;
use Illuminate\Database\Seeder;

class AbilityTableSeeder extends Seeder
{
    /**
     * Run the package seeder.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        Ability::create([
            'name' => 'news-comment',
            'display_name' => '评论资讯',
            'description' => '用户评论资讯权限',
        ]);
        Ability::create([
            'name' => 'news-digg',
            'display_name' => '点赞资讯',
            'description' => '用户点赞资讯权限',
        ]);
        Ability::create([
            'name' => 'news-collection',
            'display_name' => '收藏资讯',
            'description' => '用户收藏资讯权限',
        ]);
    }
}
