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
            'alias' => 'app端启动图广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告位图片',
                    'link' => '链接|string|必填，广告位链接',
                    'duration' => '时长|integer|必填， 广告显示时间',
                ],
            ],
            'rule' => [
                'image' => [
                    'image' => 'required|url',
                    'link' => 'required|url',
                    'duration' => 'required',
                ],
            ],
            'message' => [
                'image' => [
                    'image.required' => '广告位图片不能为空',
                    'image.url' => '广告位图片地址有误',
                    'link.required' => '广告位链接不能为空',
                    'link.url' => '广告位链接格式错误',
                    'duration' => '启动图广告时长不能为空',
                ],
            ],
        ]);
    }
}
