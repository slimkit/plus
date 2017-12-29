<?php

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
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSetUser()
    {
        $user = factory(UserModel::class)->create();
        $wallet = new TestWalletSetUser($user);

        $this->assertSame($user->id, $wallet->getUser()->id);
    }
}

class TestWalletSetUser extends Wallet
{
    public function getUser()
    {
        return $this->user;
    }
}
