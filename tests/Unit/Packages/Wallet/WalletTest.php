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

namespace Zhiyi\Plus\Tests\Unit\Packages\Wallet;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Zhiyi\Plus\Models\NewWallet as WalletModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Packages\Wallet\Wallet;
use Zhiyi\Plus\Tests\TestCase;

class WalletTest extends TestCase
{
    /**
     * Test Wallet::class implements.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testClassImplements()
    {
        $wallet = new Wallet();
        self::assertInstanceOf(Arrayable::class, $wallet);
        self::assertInstanceOf(Jsonable::class, $wallet);
        self::assertInstanceOf(JsonSerializable::class, $wallet);
    }

    /**
     * Test setUser method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSetUser()
    {
        $this->expectExceptionMessage('传递的不是一个用户');
        $this->expectException(\Exception::class);
        $user = UserModel::factory()->make();
        $user->id = 1;

        // Create a wallet mock.
        $wallet = $this->getMockBuilder(TestWalletSetUser::class)
                       ->onlyMethods(['userFindOrFail', 'resolveWallet'])
                       ->getMock();
        $wallet->expects(self::once())
               ->method('userFindOrFail')
               ->willReturn($user);
        $wallet->expects(self::exactly(2))
               ->method('resolveWallet')
               ->will(self::returnArgument(0));

        $wallet->setUser($user->id);
        self::assertInstanceOf(UserModel::class, $wallet->getUser());
        self::assertSame($user->id, $wallet->getUser()->id);

        $wallet->setUser($user);
        self::assertSame($user->id, $wallet->getUser()->id);

        // @expectedException \Exception
        $wallet->setUser([]);
    }

    /**
     * Test getWalletModel method.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage 没有设置钱包用户
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGetWalletModel()
    {
        $this->withoutExceptionHandling();
        $user = UserModel::factory()->make(['id' => 1]);

        // Create a wallet mock.
        $wallet = $this->getMockBuilder(Wallet::class)
                       ->onlyMethods(['walletFind'])
                       ->getMock();

        $wallet->expects(self::once())
               ->method('walletFind')
               ->willReturn(null);
        $wallet->setUser($user);

        self::assertInstanceOf(WalletModel::class, $wallet->getWalletModel());

        // test exception.
        $wallet = new Wallet();
        $wallet->getWalletModel();
    }

    /**
     * Test increment method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testIncrement()
    {
        // Create a wallet model mock.
        $model = $this->getMockBuilder(WalletModel::class)
                      ->addMethods(['save'])
                      ->getMock();
        $model->expects(self::once())
              ->method('save')
              ->willReturn($model);

        // Create a Wallet mock.
        $wallet = $this->getMockBuilder(Wallet::class)
                       ->addMethods(['getWalletModel'])
                       ->getMock();
        $wallet->expects(self::exactly(3))
               ->method('getWalletModel')
               ->willReturn($model);

        $amount = 100;
        $wallet->increment($amount);

        self::assertSame($amount, $wallet->getWalletModel()->balance);
        self::assertSame($amount, $wallet->getWalletModel()->total_income);
    }

    /**
     * Test decrement method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testDecrement()
    {
        // Create a wallet model mock.
        $model = $this->getMockBuilder(WalletModel::class)
                      ->addMethods(['save'])
                      ->getMock();
        $model->expects(self::once())
              ->method('save')
              ->willReturn($model);

        // Create a Wallet mock.
        $wallet = $this->getMockBuilder(Wallet::class)
                       ->addMethods(['getWalletModel'])
                       ->getMock();
        $wallet->expects(self::exactly(3))
               ->method('getWalletModel')
               ->willReturn($model);

        $amount = 100;
        $wallet->decrement($amount);

        self::assertSame(-$amount, $wallet->getWalletModel()->balance);
        self::assertSame($amount, $wallet->getWalletModel()->total_expenses);
    }

    /**
     * Test enough method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testEnough()
    {
        $model = new WalletModel();
        $model->balance = 100;

        // Create a Wallet::class mock.
        $wallet = $this->getMockBuilder(Wallet::class)
                       ->addMethods(['getWalletModel'])
                       ->getMock();
        $wallet->expects(self::exactly(2))
               ->method('getWalletModel')
               ->willReturn($model);

        self::assertTrue($wallet->enough(100));
        self::assertFalse($wallet->enough(200));
    }

    /**
     * Test toArray method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testToArray()
    {
        $array = ['test' => true];

        // Create a WalletModel::class mock.
        $model = $this->getMockBuilder(WalletModel::class)
                      ->addMethods(['toArray'])
                      ->getMock();
        $model->expects(self::once())
              ->method('toArray')
              ->willReturn($array);

        // Create a Wallet::class mock.
        $wallet = $this->getMockBuilder(Wallet::class)
                       ->addMethods(['getWalletModel'])
                       ->getMock();
        $wallet->expects(self::once())
               ->method('getWalletModel')
               ->willReturn($model);

        $this->assertArraySubset($array, $wallet->toArray());
    }

    /**
     * Test jsonSerialize method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testJsonSerialize()
    {
        $array = ['test' => true];

        // Create a Wallet::class mock.
        $wallet = $this->getMockBuilder(Wallet::class)
                       ->addMethods(['toArray'])
                       ->getMock();
        $wallet->expects(self::once())
               ->method('toArray')
               ->willReturn($array);

        $this->assertArraySubset($array, $wallet->jsonSerialize());
    }

    /**
     * Test toJson method.
     *
     * @expectedException \RuntimeException
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testToJson()
    {
        // 有效的
        $array = ['test' => true];

        // 无效的
        $text = "\xB1\x31";

        // Create a Wallet::class mock.
        $wallet = $this->getMockBuilder(Wallet::class)
                       ->addMethods(['jsonSerialize'])
                       ->getMock();
        $wallet->expects(self::exactly(2))
               ->method('jsonSerialize')
               ->will(self::onConsecutiveCalls($array, $text));

        $result = $wallet->toJson(0);
        // dd($result);
        self::assertSame(json_encode($array, 0), $result);

        // Test exception
        $wallet->toJson();
    }

    /**
     * Test __toString method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function test__toString()
    {
        $text = 'ThinkSNS Plus';
        // Create a Wallet::class mock.
        $wallet = $this->getMockBuilder(Wallet::class)
                       ->addMethods(['toJson'])
                       ->getMock();
        $wallet->expects(self::once())
               ->method('toJson')
               ->willReturn($text);

        self::assertSame($text, (string) $wallet);
    }
}

class TestWalletSetUser extends Wallet
{
    public function getUser()
    {
        return $this->user;
    }
}
