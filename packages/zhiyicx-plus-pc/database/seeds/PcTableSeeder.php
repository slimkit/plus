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

namespace SlimKit\PlusPc\Database\Seeders;

use Illuminate\Database\Seeder;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\Navigation;
use Zhiyi\Plus\Models\AdvertisingSpace;

class PcTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createData();

        Navigation::create([
            'name' => '动态',
            'app_name' => 'feeds',
            'url' => '/feeds',
            'target' => '_self',
            'status' => 1,
            'position' => 0,
            'parent_id' => 0,
            'order_sort' => 0,
        ]);
        Navigation::create([
            'name' => '资讯',
            'app_name' => 'news',
            'url' => '/news',
            'target' => '_self',
            'status' => 1,
            'position' => 0,
            'parent_id' => 0,
            'order_sort' => 0,
        ]);
        Navigation::create([
            'name' => '找伙伴',
            'app_name' => 'people',
            'url' => '/people',
            'target' => '_self',
            'status' => 1,
            'position' => 0,
            'parent_id' => 0,
            'order_sort' => 0,
        ]);
        Navigation::create([
            'name' => '话题',
            'app_name' => 'topic',
            'url' => '/topic',
            'target' => '_self',
            'status' => 1,
            'position' => 0,
            'parent_id' => 0,
            'order_sort' => 0,
        ]);
    }

    /**
     * create ads data.
     *
     * @return void
     */
    protected function createData()
    {
        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:top',
            'alias' => 'PC端资讯首页banner',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图',
                    'link' => '链接|string',
                ],
            ],
            'rule' => [
                'image' => [
                    'link' => 'url',
                    'image' => 'required|url',
                ],
            ],
            'message' => [
                'image' => [
                    'image.required' => '广告图不能为空',
                ],
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:right',
            'alias' => 'PC端资讯右侧广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图',
                    'link' => '链接|string',
                ],
            ],
            'rule' => [
                'image' => [
                    'link' => 'url',
                    'image' => 'required|url',
                ],
            ],
            'message' => [
                'image' => [
                    'image.required' => '广告图不能为空',
                ],
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:feeds:right',
            'alias' => 'PC端动态右侧广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图',
                    'link' => '链接|string',
                ],
            ],
            'rule' => [
                'image' => [
                    'link' => 'url',
                    'image' => 'required|url',
                ],
            ],
            'message' => [
                'image' => [
                    'image.required' => '广告图不能为空',
                ],
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:list',
            'alias' => 'PC端资讯列表广告',
            'allow_type' => 'pc:news:list',
            'format' => [
                'pc:news:list' => [
                    'name' => '用户名|string|必填，用户名',
                    'content' => '内容|string|广告内容',
                    'image' => '图片|string|广告图片',
                    'time' => '时间|date|广告动态时间',
                    'link' => '链接|string|广告链接',
                ],
            ],
            'rule' => [
                'pc:news:list' => [
                    'name' => 'required',
                    'image' => 'url',
                    'time' => 'required|date',
                    'link' => 'required|url',
                    'content' => 'required',
                ],
            ],
            'message' => [
                'pc:news:list' => [
                    'name.required' => '广告用户名不能为空',
                    'image.required' => '广告图片链接不能为空',
                    'time.required' => '时间必填',
                    'time.date' => '时间格式错误',
                    'content.required' => '内容必填',
                    'link.required' => '广告连接不能为空',
                    'link.url' => '广告链接无效',
                ],
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:feeds:list',
            'alias' => 'PC端动态列表广告',
            'allow_type' => 'pc:feeds:list',
            'format' => [
                'pc:feeds:list' => [
                    'avatar' => '头像图|string|必填，头像',
                    'name' => '用户名|string|必填，用户名',
                    'content' => '内容|string|广告内容',
                    'image' => '图片|string|广告图片',
                    'time' => '时间|date|广告动态时间',
                    'link' => '链接|string|广告链接',
                ],
            ],
            'rule' => [
                'pc:feeds:list' => [
                    'name' => 'required',
                    'image' => 'url',
                    'avatar' => 'required|url',
                    'time' => 'required|date',
                    'link' => 'required|url',
                    'content' => 'required',
                ],
            ],
            'message' => [
                'pc:feeds:list' => [
                    'name.required' => '广告用户名不能为空',
                    'image.required' => '广告图片链接不能为空',
                    'avatar.required' => '头像图链接不能为空',
                    'avatar.url' => '头像图链接无效',
                    'time.required' => '时间必填',
                    'time.date' => '时间格式错误',
                    'content.required' => '内容必填',
                    'link.required' => '广告连接不能为空',
                    'link.url' => '广告链接无效',
                ],
            ],
        ]);
    }
}
