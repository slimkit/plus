<?php

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
