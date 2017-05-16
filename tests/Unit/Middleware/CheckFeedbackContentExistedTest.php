<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Http\Middleware\V1\CheckFeedbackContentExisted;

class CheckFeedbackContentExistedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test Check feedback without content.
     *
     * @author bs<414606094@qq.com>
     */
    public function testHandle()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['input'])
            ->getMock();
        $map = [
            ['content', null, ''],
        ];

        $request->expects($this->any())
            ->method('input')
            ->will($this->returnValueMap($map));

        $response = TestResponse::fromBaseResponse(
            with(new CheckFeedbackContentExisted())->handle($request, function () {
            })
        );
        $response->assertStatus(400);
    }
}
