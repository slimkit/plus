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

class AuthLoginTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
    }

    /**
     * Test User ID login.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testUserLogin()
    {
        $response = $this->json('POST', 'api/v2/auth/login', [
            'login' => $this->user->id,
            'password' => 'password',
        ]);

        $this->assertLoginResponse($response);
    }

    /**
     * 使用错误的密码将返回403状态码
     */
    public function test_user_can_not_login_with_wrong_password()
    {
        $response = $this->json('POST', 'api/v2/auth/login', [
            'login' => $this->user->id,
            'password' => 'secret',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Assert login response.
     *
     * @param [type] $response
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function assertLoginResponse($response)
    {
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }
}
