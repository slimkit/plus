<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Http\Middleware\VerifyPhoneNumber;

class VerifySendPhoneCodeTypeTest extends TestCase
{
    /**
     * test verify phone number.
     *
     * @author bs<414606094@qq.com>
     */
    public function testHandle()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['post'])
            ->getMock();

        $request->expects($this->any())
            ->method('post')
            ->willReturn([
                'type' => 'test',
            ]);

        $response = TestResponse::fromBaseResponse(
            with(new VerifyPhoneNumber())->handle($request, function () {
            })
        );
        $response->assertStatus(403);
    }
}
