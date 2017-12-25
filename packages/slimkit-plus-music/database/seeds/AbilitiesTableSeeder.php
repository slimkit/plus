<?php

namespace Zhiyi\Plus\Packages\Music\Seeds;

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Ability as AbilityModel;

class AbilitiesTableSeeder extends Seeder
{
    /**
     * Run the package insert abilities table.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        AbilityModel::create([
            'name' => 'music-comment',
            'display_name' => '评论歌曲',
            'description' => '用户评论歌曲权限',
        ]);
        AbilityModel::create([
            'name' => 'music-digg',
            'display_name' => '点赞歌曲',
            'description' => '用户点赞歌曲权限',
        ]);
        AbilityModel::create([
            'name' => 'music-collection',
            'display_name' => '收藏歌曲',
            'description' => '用户收藏歌曲权限',
        ]);
    }
}
