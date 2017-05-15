<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\Following;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Http\Middleware\V1\CheckIsFollow;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CheckIsFollowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test Check Follow with followed.
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

        Following::insert(['user_id' => $user->id, 'following_user_id' => $following_user->id]);

        $response = TestResponse::fromBaseResponse(
            with(new CheckIsFollow())->handle($request, function () {
            })
        );
        $response->assertStatus(400);
    }
}
