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

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
        factory(WalletOrderModel::class)->create(['owner_id' => $this->user->id]);
        factory(WalletOrderModel::class)->create(['owner_id' => $this->user->id]);
        factory(WalletOrderModel::class)->create(['owner_id' => $this->user->id]);
    }

    /**
     * 测试获取订单流水.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetOrders()
    {
        $token = $this->guard()->login($this->user);

        $firstrResponse = $this->json('GET', '/api/v2/plus-pay/orders?limit=1', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $firstrResponse->assertStatus(200);

        $data = $firstrResponse->json()[0];
        $this->assertTrue(count($firstrResponse->json()) === 1);

        $this->assertOrderData($data);

        $after = $this->json('GET', '/api/v2/plus-pay/orders?limit=1&after='.$data['id'], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $after->assertStatus(200);

        $afterData = $after->json()[0];

        $this->assertOrderData($afterData);
        $this->assertTrue(count($after->json()) === 1);
        $this->assertTrue($afterData['id'] < $data['id']);
    }

    /**
     * 断言订单基本结构.
     *
     * @param array $singleData
     * @return void
     * @author BS <414606094@qq.com>
     */
    protected function assertOrderData(array $singleData)
    {
        $this->assertArrayHasKey('id', $singleData);
        $this->assertArrayHasKey('owner_id', $singleData);
        $this->assertArrayHasKey('target_type', $singleData);
        $this->assertArrayHasKey('target_id', $singleData);
        $this->assertArrayHasKey('title', $singleData);
        $this->assertArrayHasKey('body', $singleData);
        $this->assertArrayHasKey('type', $singleData);
        $this->assertArrayHasKey('amount', $singleData);
        $this->assertArrayHasKey('state', $singleData);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard(): Guard
    {
        return Auth::guard('api');
    }

    protected function tearDown()
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
