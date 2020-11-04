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
use Zhiyi\Plus\Models\VerificationCode;

class VerificationCodeFactory extends Factory
{
    protected $model = VerificationCode::class;

    public function definition()
    {
        return [
            'user_id' => null,
            'channel' => 'mail',
            'account' => $this->faker->safeEmail,
            'code' => $this->faker->numberBetween(1000, 999999),
            'state' => 0,
        ];
    }
}
