<?php

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

namespace Zhiyi\PlusGroup\Seeds;

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\AdvertisingSpace;

class AdvertisingSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdvertisingSpace::create([
            'channel' => 'group',
            'space' => 'group:index:top',
            'alias' => '移动端圈子首页顶部广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图',
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
            'channel' => 'group',
            'space' => 'group:single',
            'alias' => '移动端圈子帖子详情广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图',
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
    }
}
