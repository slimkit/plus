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
use Zhiyi\Plus\Tests\TestCase;

class VerifyCodeTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create(['phone' => '18908019700', 'email' => '182478966@qq.com']);
    }

    /**
     * 测试通过手机号获取验证码.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetVerifyCodeByPhone()
    {
        $this->withoutExceptionHandling();
        $token = $this->guard()->login($this->user);

        $responseByPhone = $this->json('POST', 'api/v2/verifycodes?token='.$token, [
            'phone' => $this->user->phone,
        ]);

        $this->assertLoginResponse($responseByPhone);
    }

    /**
     * 测试通过邮箱获取验证码.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetVerifyCodeByEmail()
    {
        $token = $this->guard()->login($this->user);

        $responseByEmail = $this->json('POST', 'api/v2/verifycodes?token='.$token, [
            'email' => $this->user->email,
        ]);

        $this->assertLoginResponse($responseByEmail);
    }

    /**
     * Assert login response.
     *
     * @param $response
     *
     * @return void
     */
    protected function assertLoginResponse($response)
    {
        $response
            ->assertStatus(202)
            ->assertJsonStructure(['message']);
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
