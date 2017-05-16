<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Http\Middleware\V1\VerifySendPhoneCodeType;

class VerifySendPhoneCodeTypeTest extends TestCase
{
    /**
     * test verify send phone number type.
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
            with(new VerifySendPhoneCodeType())->handle($request, function () {
            })
        );
        $response->assertStatus(403);
    }
}
