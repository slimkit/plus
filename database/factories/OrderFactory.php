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

use Faker\Generator as Faker;

$factory->define(Zhiyi\Plus\Models\WalletOrder::class, function (Faker $faker) {
    static $user_id;

    return [
        'owner_id' => $user_id,
        'target_type' => 'user',
        'target_id' => 'id',
        'title' => '测试标题',
        'body' => '测试内容',
        'type' => 1,
        'amount' => random_int(1, 999999),
        'state' => rand(-1, 1),
    ];
});
