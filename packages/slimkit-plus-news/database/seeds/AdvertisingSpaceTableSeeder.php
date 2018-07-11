<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\AdvertisingSpace;

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
            'alias' => '移动端资讯列表顶部广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图，尺寸：1080px * 561px',
                    'link' => '链接|string|必填，广告链接',
                ],
            ],
            'rule' => [
                'image' => [
                    'image' => 'required|url',
                    'link'  => 'required|url',
                ],
            ],
            'message' => [
                'image' => [
                    'image.required' => '广告图链接不能为空',
                    'image.url' => '广告图链接无效',
                    'link.required' => '广告链接不能为空',
                    'link.url' => '广告链接无效',
                ],
            ],
        ]);
        AdvertisingSpace::create([
            'channel' => 'news',
            'space' => 'news:single',
            'alias' => '移动端资讯详情广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图，一张图： 1020px * 180px、两张图：502px x 180px、三张图： 340px x 180px;',
                    'link' => '链接|string|必填，广告链接',
                ],
            ],
            'rule' => [
                'image' => [
                    'image' => 'required|url',
                    'link'  => 'required|url',
                ],
            ],
            'message' => [
                'image' => [
                    'image.required' => '广告图链接不能为空',
                    'image.url' => '广告图链接无效',
                    'link.required' => '广告链接不能为空',
                    'link.url' => '广告链接无效',
                ],
            ],
        ]);
        AdvertisingSpace::create([
            'channel' => 'news',
            'space' => 'news:list:analog',
            'alias' => '移动端资讯列表模拟数据广告',
            'allow_type' => 'news:analog',
            'format' => [
                'news:analog' => [
                    'title' => '标题|string|必填，广告资讯标题',
                    'image' => '图片|image|必填，广告图片，尺寸：285px * 204px',
                    'from' => '来源|string|必填，广告资讯来源',
                    'time' => '时间|date|必填，广告资讯时间',
                    'link' => '链接|string|必填，广告链接',
                ],
            ],
            'rule' => [
                'news:analog' => [
                    'image' => 'required|url',
                    'link'  => 'required|url',
                    'time' => 'required|date',
                    'title' => 'required',
                    'from' => 'required',
                ],
            ],
            'message' => [
                'news:analog' => [
                   'image.required' => '广告图链接不能为空',
                   'image.url' => '广告图链接无效',
                   'link.required' => '广告链接不能为空',
                   'link.url' => '广告链接无效',
                   'time.required' => '广告资讯时间必填',
                   'time.date' => '时间格式错误',
                   'from.required' => '广告资讯来源必填',
                   'title.required' => '广告资讯标题必填',
                ],
            ],
        ]);
    }
}
