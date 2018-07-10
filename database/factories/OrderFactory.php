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
