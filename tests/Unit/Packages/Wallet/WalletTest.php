<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\Tests\Unit\Packages\Wallet;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Packages\Wallet\Wallet;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WalletTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test setUser method.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage 传递的不是一个用户
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSetUser()
    {
        $user = factory(UserModel::class)->create();
        $wallet = new TestWalletSetUser($user);

        $this->assertSame($user->id, $wallet->getUser()->id);

        $wallet->setUser($user->id);
        $this->assertInstanceOf(UserModel::class, $wallet->getUser());
        $this->assertSame($user->id, $wallet->getUser()->id);

        // @expectedException \Exception
        $wallet->setUser([]);
    }
}

class TestWalletSetUser extends Wallet
{
    public function getUser()
    {
        return $this->user;
    }
}
