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
        $response = $this->get('/api/v2/locations/search?name=北京');

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
        $response = $this->get('/api/v2/locations/hots');

        $response->assertStatus(200);
    }
}
