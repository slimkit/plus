<?php

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

use Faker\Generator as Faker;

$factory->define(Zhiyi\Plus\Models\Advertising::class, function (Faker $faker) {
    return [
        'space_id' => 1,
        'title' => '测试标题',
        'type' => 'image',
        'data' => [
            'image' => 'http://xxx/xxx.jpg',
            'url' => 'http://www.xxxxx.com',
        ],
        'sort' => 0,
    ];
});
