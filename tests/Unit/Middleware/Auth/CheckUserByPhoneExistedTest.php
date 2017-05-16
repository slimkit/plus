<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Http\Middleware\V1\CheckUserByPhoneExisted;

class CheckUserByPhoneExistedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test check user with phone exited.
     *
     * @author bs<414606094@qq.com>
     */
    public function testHandle()
    {
        $user = factory(User::class)->make();

        $phone = $user->phone;
        $user->delete();

        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['input'])
            ->getMock();

        $request->expects($this->any())
            ->method('input')
            ->with('phone')
            ->willReturn($phone);
        $response = TestResponse::fromBaseResponse(
            with(new CheckUserByPhoneExisted())->handle($request, function () {
            })
        );
        $response->assertStatus(404);
    }
}
