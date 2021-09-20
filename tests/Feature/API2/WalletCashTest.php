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
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\WalletCash as WalletCashModel;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\Tests\TestCase;

class WalletCashTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
        $this->user->newWallet()->update(['balance' => 999999, 'total_income' => 0, 'total_expenses' => 0]);

        factory(WalletCashModel::class)->create(['user_id' => $this->user->id]);
        factory(WalletCashModel::class)->create(['user_id' => $this->user->id]);
        factory(WalletCashModel::class)->create(['user_id' => $this->user->id]);
        setting('wallet')->set('cash-types', ['alipay']);
    }

    /**
     * 测试获取提现记录.
     *
     * @return void
     *
     * @author BS <414606094@qq.com>
     */
    public function testGetCashes()
    {
        $firstrResponse = $this->actingAs($this->user, 'api')->json('GET', '/api/v2/plus-pay/cashes?limit=1');
        $firstrResponse->assertStatus(200);

        $data = $firstrResponse->json()[0];

        $this->assertTrue(count($firstrResponse->json()) === 1);

        $this->assertOrderData($data);

        $after = $this->actingAs($this->user, 'api')->json('GET', '/api/v2/plus-pay/cashes?after='.$data['id']);
        $after->assertStatus(200);
        $afterData = $after->json()[0];

        $this->assertOrderData($afterData);
        $this->assertTrue(count($after->json()) === 2);
        $this->assertTrue($afterData['id'] < $data['id']);
    }

    /**
     * 测试发起提现.
     *
     * @return void
     *
     * @author BS <414606094@qq.com>
     */
    public function testCreateCash()
    {
        $response = $this->actingAs($this->user, 'api')->json('post', '/api/v2/plus-pay/cashes', [
            'value' => setting('wallet', 'cash-min-amount', 100),
            'type' => 'alipay',
            'account' => 'asas@aaa.com',
        ]);

        $response->assertStatus(201);
    }

    /**
     * 断言提现数据基本结构.
     *
     * @param  array  $singleData
     * @return void
     *
     * @author BS <414606094@qq.com>
     */
    protected function assertOrderData(array $singleData)
    {
        $this->assertArrayHasKey('id', $singleData);
        $this->assertArrayHasKey('value', $singleData);
        $this->assertArrayHasKey('account', $singleData);
        $this->assertArrayHasKey('status', $singleData);
        $this->assertArrayHasKey('remark', $singleData);
    }

    protected function tearDown()
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
