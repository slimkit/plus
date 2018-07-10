<?php

declare(strict_types=1);

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

namespace Zhiyi\Plus\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VerifyCodeTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create(['phone' => '13730441111', 'email' => 'aaa@bbb.com']);
    }

    /**
     * 测试通过手机号获取验证码.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetVerifyCodeByPhone()
    {
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
