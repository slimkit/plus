<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Http\Middleware\V1\VerifyUserNameRole;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VerifyUserNameRoleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test min length.
     *
     * @author bs<414606094@qq.com>
     */
    public function testMinLengthName()
    {
        $request = $this->mockRequestName('123');

        $response = TestResponse::fromBaseResponse(
            with(new VerifyUserNameRole())->handle($request, function () {
            })
        );
        $response->assertStatus(403);
    }

    /**
     * test max length.
     *
     * @author bs<414606094@qq.com>
     */
    public function testMaxLengthName()
    {
        $request = $this->mockRequestName('123123123123123123123123123123123123123123123123123123123123123123123123123123123');

        $response = TestResponse::fromBaseResponse(
            with(new VerifyUserNameRole())->handle($request, function () {
            })
        );
        $response->assertStatus(403);
    }

    protected function mockRequestName($name):Request
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['input'])
            ->getMock();

        $request->expects($this->any())
            ->method('input')
            ->with('name')
            ->willReturn($name);

        return $request;
    }
}
