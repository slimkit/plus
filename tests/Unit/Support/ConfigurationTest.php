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

namespace Zhiyi\Plus\Tests\Unit\Support;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Support\Configuration;

class ConfigurationTest extends TestCase
{
    /**
     * Test parse method.
     *
     * @return void
     */
    public function testParse()
    {
        $instance = new Configuration($this->app);
        $origin = [
            'app1' => [1, 2, 4],
            'app2' => [
                'a' => 'a',
                'b' => 'b',
            ],
        ];
        $value = [
            'app1' => [1, 2, 4],
            'app2.a' => 'a',
            'app2.b' => 'b',
        ];

        $this->assertEquals($value, $instance->parse($origin));
    }
}
