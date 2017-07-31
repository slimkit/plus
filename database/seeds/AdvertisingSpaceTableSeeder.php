<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\AdvertisingSpace;

class AdvertisingSpaceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdvertisingSpace::create([
            'channel' => 'boot',
            'space' => 'boot',
            'alias' => '启动图广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);
    }
}
