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
use Zhiyi\Plus\Tests\TestCase;

class WalletTransformTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
        $this->user->newWallet()->update(['balance' => 999999]);
    }

    /**
     * 测试发起转换积分.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testTransfer()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v2/plus-pay/transform', ['amount' => 2121]);

        $response->assertStatus(201);
    }

    protected function tearDown()
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
