<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Http\Middleware\V1\CheckUserByPhoneNotExisted;

class CheckUserByPhoneNotExistedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test check user with phone exited.
     *
     * @author bs<414606094@qq.com>
     */
    public function testHandle()
    {
        $user = factory(User::class)->create();
        $phone = $user->phone;

        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['input'])
            ->getMock();

        $request->expects($this->any())
            ->method('input')
            ->with('phone')
            ->willReturn($phone);
        $response = TestResponse::fromBaseResponse(
            with(new CheckUserByPhoneNotExisted())->handle($request, function () {
            })
        );
        $response->assertStatus(403);
    }
}
