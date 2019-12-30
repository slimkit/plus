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

namespace Zhiyi\Plus\Tests\Feature\API2;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Models\Advertising as AdvertisingModel;
use Zhiyi\Plus\Tests\TestCase;

class AdvertisingTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 测试获取广告位.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetAdvertisingSpace()
    {
        $response = $this->json('GET', '/api/v2/advertisingspace');

        collect($response->json())->map(function ($adspace) {
            $this->assertArrayHasKey('id', $adspace);
            $this->assertArrayHasKey('channel', $adspace);
            $this->assertArrayHasKey('space', $adspace);
            $this->assertArrayHasKey('alias', $adspace);
            $this->assertArrayHasKey('allow_type', $adspace);
            $this->assertArrayHasKey('format', $adspace);
        });
    }

    /**
     * 测试获取一个广告位的广告列表.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetAdvertising()
    {
        $spaces = $this->json('GET', '/api/v2/advertisingspace')->json();

        collect($spaces)->map(function ($space) {
            factory(AdvertisingModel::class)->create(['space_id' => $space['id']]);
            factory(AdvertisingModel::class)->create(['space_id' => $space['id']]);
        });

        $response = $this->json('GET', '/api/v2/advertisingspace/'.$spaces[0]['id'].'/advertising');

        collect($response->json())->map(function ($ad) {
            $this->assertAdvertisingData($ad);
        });
    }

    /**
     * 测试批量获取广告.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetMuliteAdvertising()
    {
        $spaces = $this->json('GET', '/api/v2/advertisingspace')->json();

        collect($spaces)->map(function ($space) {
            factory(AdvertisingModel::class)->create(['space_id' => $space['id']]);
            factory(AdvertisingModel::class)->create(['space_id' => $space['id']]);
        });

        $response = $this->json('GET', '/api/v2/advertisingspace/advertising?space='.$spaces[0]['id']);

        collect($response->json())->map(function ($ad) {
            $this->assertAdvertisingData($ad);
        });
    }

    /**
     * 断言广告基本结构.
     */
    protected function assertAdvertisingData(array $singleData)
    {
        $this->assertArrayHasKey('id', $singleData);
        $this->assertArrayHasKey('space_id', $singleData);
        $this->assertArrayHasKey('title', $singleData);
        $this->assertArrayHasKey('type', $singleData);
        $this->assertArrayHasKey('data', $singleData);
        $this->assertArrayHasKey('sort', $singleData);
    }
}
