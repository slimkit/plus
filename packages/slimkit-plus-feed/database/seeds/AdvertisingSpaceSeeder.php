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

namespace SlimKit\Plus\Packages\Feed\Seeds;

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\AdvertisingSpace;

class AdvertisingSpaceSeeder extends Seeder
{
    public function run()
    {
        AdvertisingSpace::create([
            'channel' => 'feed',
            'space' => 'feed:list:top',
            'alias' => 'App 动态列表顶部广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，图片，尺寸：1080px * 561px',
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
            'channel' => 'feed',
            'space' => 'feed:single',
            'alias' => '移动端动态详情广告',
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
            'channel' => 'feed',
            'space' => 'feed:list:analog',
            'alias' => '移动端动态列表模拟数据广告',
            'allow_type' => 'feed:analog',
            'format' => [
                'feed:analog' => [
                    'avatar' => '头像图|image|必填，头像 尺寸: 196px * 196px',
                    'name' => '用户名|string|必填，用户名',
                    'content' => '内容|string|必填，广告动态内容',
                    'image' => '图片|image|广告图，尺寸：777px x 387px',
                    'time' => '时间|date|必填，广告动态时间',
                    'link' => '链接|string|必填，广告链接',
                ],
            ],
            'rule' => [
                'feed:analog' => [
                    'image' => 'url',
                    'link'  => 'required|url',
                    'time' => 'required|date',
                    'content' => 'required',
                    'avatar' => 'required|url',
                    'name' => 'required',
                ],
            ],
            'message' => [
                'feed:analog' => [
                    'image.required' => '广告图链接不能为空',
                    'image.url' => '广告图链接无效',
                    'link.required' => '广告链接不能为空',
                    'link.url' => '广告链接无效',
                    'avatar.required' => '头像图链接必填',
                    'avatar.url' => '头像图链接无效',
                    'time.required' => '时间必填',
                    'content.required' => '内容必填',
                    'time.date' => '时间格式错误',
                    'name.required' => '用户名必填',
                ],
            ],
        ]);
    }
}
