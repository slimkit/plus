<?php

namespace SlimKit\Plus\Packages\News\Seeds;

use Illuminate\Database\Seeder;

class AdvertisingSpaceTableSeeder extends Seeder
{
    /**
     * Run the ad seeder.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        AdvertisingSpace::create([
            'channel' => 'news',
            'space' => 'news:list:top',
            'alias' => '资讯列表顶部广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);
        AdvertisingSpace::create([
            'channel' => 'news',
            'space' => 'news:single',
            'alias' => '资讯详情广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);
        AdvertisingSpace::create([
            'channel' => 'news',
            'space' => 'news:list:analog',
            'alias' => '资讯列表模拟数据广告',
            'allow_type' => 'news:analog',
            'format' => [
                'news:analog' => [
                    'title' => '标题|string',
                    'image' => '图片|image',
                    'from' => '来源|string',
                    'time' => '时间|date',
                    'link' => '链接|string',
                ],
            ],
        ]);
    }
}
