<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Http\Middleware\V1\VerifyPhoneNumber;

class VerifyPhoneNumberTest extends TestCase
{
    /**
     * test verify phone number with empty.
     *
     * @author bs<414606094@qq.com>
     */
    public function testHandle()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['all'])
            ->getMock();

        $request->expects($this->any())
            ->method('all')
            ->willReturn([
                'phone' => '',
            ]);

        $response = TestResponse::fromBaseResponse(
            with(new VerifyPhoneNumber())->handle($request, function () {
            })
        );

        $response->assertStatus(403);
    }

    /**
     * @author bs<414606094@qq.com>
     */
    public function testWrongNumber()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['all'])
            ->getMock();

        $request->expects($this->any())
            ->method('all')
            ->willReturn([
                'phone' => '11111111111',
            ]);

        $response = TestResponse::fromBaseResponse(
            with(new VerifyPhoneNumber())->handle($request, function () {
            })
        );

        $response->assertStatus(403);
    }
}
