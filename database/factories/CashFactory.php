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

$factory->define(Zhiyi\Plus\Models\WalletCash::class, function (Faker $faker) {
    static $user_id;

    return [
        'user_id' => $user_id,
        'type' => 'alipay',
        'account' => '1212121212',
        'value' => random_int(1, 999999),
        'status' => rand(0, 2),
        'remark' => '',
    ];
});
