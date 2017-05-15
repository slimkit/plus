<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Http\Middleware\V1\VerifyPhoneCode;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VerifyPhoneCodeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test verify phone without code exited.
     *
     * @author bs<414606094@qq.com>
     */
    public function testHandle()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['input'])
            ->getMock();

        $map = [
            ['phone', null, '111111111111'],
            ['code', null, '1234'],
        ];

        $request->expects($this->any())
            ->method('input')
            ->will($this->returnValueMap($map));

        $response = TestResponse::fromBaseResponse(
            with(new VerifyPhoneCode())->handle($request, function () {
            })
        );

        $response->assertStatus(403);
    }
}
