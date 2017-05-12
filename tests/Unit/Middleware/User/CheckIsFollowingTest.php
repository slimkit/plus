<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Http\Middleware\CheckIsFollowing;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CheckIsFollowingTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test Check Follow without followed.
     *
     * @author bs<414606094@qq.com>
     */
    public function testHandle()
    {
        $user = factory(User::class)->create();
        $following_user = factory(User::class)->create();

        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['user'])
            ->getMock();

        $request->expects($this->any())
            ->method('user')
            ->willReturn($user);
        $request->user_id = $following_user->id;

        $response = TestResponse::fromBaseResponse(
            with(new CheckIsFollowing())->handle($request, function () {
            })
        );
        $response->assertStatus(400);
    }
}
