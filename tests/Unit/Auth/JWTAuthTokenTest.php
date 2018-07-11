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

namespace Zhiyi\Plus\Tests\Unit\Auth;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JWTAuthTokenTest extends TestCase
{
    use DatabaseTransactions;

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
