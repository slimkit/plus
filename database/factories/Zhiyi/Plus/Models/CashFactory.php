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

namespace Database\Factories\Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zhiyi\Plus\Models\WalletCash;

class CashFactory extends Factory
{
    public static $user_id;
    protected $model = WalletCash::class;

    public function definition()
    {
        return [
            'user_id' => $this->user_id,
            'type' => 'alipay',
            'account' => '1212121212',
            'value' => random_int(1, 999999),
            'status' => rand(0, 2),
            'remark' => '',
        ];
    }
}
