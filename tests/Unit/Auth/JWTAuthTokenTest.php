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

namespace Zhiyi\Plus\Tests\Unit\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

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
