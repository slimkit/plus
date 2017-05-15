<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Http\Middleware\V1\CheckDeviceCodeExisted;

class CheckDeviceCodeExistedTest extends TestCase
{
    /**
     * test check device code whit empty.
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
                'device_code' => '',
            ]);

        $response = TestResponse::fromBaseResponse(
            with(new CheckDeviceCodeExisted())->handle($request, function () {
            })
        );
        $response->assertStatus(422);
    }
}
