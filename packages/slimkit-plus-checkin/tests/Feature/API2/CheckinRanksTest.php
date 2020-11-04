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

namespace SlimKit\PlusCheckIn\Tests\Feature\API2;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Tests\TestCase;

class CheckinRanksTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test get checkin ranks.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGetRanks()
    {
        $response = $this->json('GET', '/api/v2/checkin-ranks');
        $json = $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->decodeResponseJson();

        $this->assertTrue(
            is_array($json)
        );
    }
}
