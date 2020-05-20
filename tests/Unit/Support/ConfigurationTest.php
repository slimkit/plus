<?php

declare(strict_types=1);

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

namespace Zhiyi\Plus\Tests\Unit\Support;

use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Tests\TestCase;

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
