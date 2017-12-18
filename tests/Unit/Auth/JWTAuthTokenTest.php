<?php

namespace Zhiyi\Plus\Tests\Unit\Auth;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JWTAuthTokenTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test guard.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGuard()
    {
        $jwtAuthToken = $this->app->make(
            \Zhiyi\Plus\Auth\JWTAuthToken::class
        );

        $this->assertInstanceOf(\Illuminate\Contracts\Auth\Guard::class, $jwtAuthToken->guard());
    }

    /**
     * Test create method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testCreate()
    {
        $jwtAuthToken = $this->app->make(\Zhiyi\Plus\Auth\JWTAuthToken::class);
        $user = factory(UserModel::class)->create();
        $token = $jwtAuthToken->create($user);

        $this->assertTrue((bool) $token);
    }

    /**
     * Test refresh method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testRefresh()
    {
        $jwtAuthToken = $this->app->make(\Zhiyi\Plus\Auth\JWTAuthToken::class);
        $user = factory(UserModel::class)->create();
        $token = $jwtAuthToken->create($user);
        $newToken = $jwtAuthToken->refresh($token);

        $this->assertTrue((bool) $newToken);
        $this->assertNotSame($token, $newToken);
    }
}
