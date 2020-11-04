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
use Zhiyi\Plus\Models\Advertising;

class AdvertisingFactory extends Factory
{
    protected $model = Advertising::class;

    public function definition()
    {
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
    }
}
