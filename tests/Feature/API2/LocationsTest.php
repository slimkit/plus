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

namespace Zhiyi\Plus\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;

class LocationsTest extends TestCase
{
    /**
     * 测试获取地区.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testSearchLocations()
    {
        $response = $this->json('GET', '/api/v2/locations/search?name='.urlencode('北京'));

        $response->assertStatus(200);
    }

    /**
     * 测试获取热门城市.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetHotLocations()
    {
        $response = $this->json('GET', '/api/v2/locations/hots');

        $response->assertStatus(200);
    }
}
