<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Http\Middleware\V1\CheckUserExsistedByUserId;

class CheckUserExsistedByUserIdTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test middleware without user_id.
     *
     * @author bs<414606094@qq.com>
     */
    public function testEmptyUserId()
    {
        $request = $this->getMockBuilder(Request::class)
            ->getMock();

        $response = TestResponse::fromBaseResponse(
            with(new CheckUserExsistedByUserId())->handle($request, function () {
            })
        );

        $response->assertStatus(400);
    }

    /**
     * test middleware without user.
     *
     * @author bs<414606094@qq.com>
     */
    public function testEmptyUser()
    {
        $user = factory(User::class)->create();
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['user_id'])
            ->getMock();

        $request->user_id = $user->id;

        $user->delete();
        $response = TestResponse::fromBaseResponse(
            with(new CheckUserExsistedByUserId())->handle($request, function () {
            })
        );

        $response->assertStatus(404);
    }

    /**
     * test middleware with same user.
     *
     * @author bs<414606094@qq.com>
     */
    public function testSameUser()
    {
        $user = factory(User::class)->create();
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['user'])
            ->getMock();

        $request->user_id = $user->id;
        $request->expects($this->any())
            ->method('user')
            ->willReturn($user);

        $response = TestResponse::fromBaseResponse(
            with(new CheckUserExsistedByUserId())->handle($request, function () {
            })
        );

        $response->assertStatus(400);
    }
}
