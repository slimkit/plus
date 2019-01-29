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

use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterVerifyCodeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 测试获取验证码.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetVerifyCode()
    {
        $registerByPhone = $this->json('POST', 'api/v2/verifycodes/register', [
            'phone' => '13730111234',
        ]);

        $registerByEmail = $this->json('POST', 'api/v2/verifycodes/register', [
            'email' => 'aaa@bbb.com',
        ]);

        $this->assertLoginResponse($registerByPhone);
        $this->assertLoginResponse($registerByEmail);
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
}
