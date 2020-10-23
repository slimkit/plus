<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Tests\Unit\Http\Controllers\APIs\V2;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTGuard;
use Zhiyi\Plus\Http\Controllers\APIs\V2\AuthController;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;

class AuthControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * Test AuthController::class guard method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGuard()
    {
        $controller = $this->getMockBuilder(AuthController::class)
            ->setMethods(null)
            ->getMock();

        // Test the AuthController::guard return instalce of Guard::class
        $this->assertInstanceOf(Guard::class, $controller->guard());

        // Test the AuthController::guard return instance of JWTGuard::class
        $this->assertInstanceOf(JWTGuard::class, $controller->guard());
    }

    /**
     * Test AuthController::class login method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testLogin()
    {
        $credentials = [
            'id'       => 1,
            'password' => 'password',
        ];
        $token = 'token';

        // Create a AuthController::class mock
        $controller = $this->getMockBuilder(AuthController::class)
            ->setMethods(['guard', 'response', 'respondWithToken'])
            ->getMock();

        // Create a Request::class mock
        $request = $this->createMock(Request::class);

        // Create a JsonResponse::class mock.
        $response = $this->getMockBuilder(JsonResponse::class)
            ->setMethods(['json'])
            ->getMock();

        // Create a JWTGuard::class mock
        $guard = $this->createMock(JWTGuard::class);

        // Mock Request::input method
        $map = [
            ['login', '', '1'],
            ['password', '', 'password'],
        ];
        $request->expects($this->exactly(6))
            ->method('input')
            ->withConsecutive(
                [$this->equalTo('login')],
                [$this->equalTo('verifiable_code')],
                [$this->equalTo('password')]
            )
            ->will($this->returnValueMap($map));

        // Mock JsonResponse::json method
        $json = ['message' => '账号或密码不正确'];
        $status = 422;
        $response->expects($this->exactly(1))
            ->method('json')
            ->with($this->equalTo($json), $this->equalTo($status))
            ->will($this->returnSelf());

        // Mock JWTGuard::attempt method
        $guard->expects($this->exactly(2))
            ->method('attempt')
            ->with($this->equalTo($credentials))
            ->will($this->onConsecutiveCalls($token, false));

        // Mock AuthController::guard method
        $controller->expects($this->exactly(2))
            ->method('guard')
            ->will($this->returnValue($guard));

        // Mock AuthController::respondWithToken method
        $controller->expects($this->exactly(1))
            ->method('respondWithToken')
            ->with($this->equalTo($token));

        // Mock AuthController::response method
        $controller->expects($this->exactly(1))
            ->method('response')
            ->will($this->returnValue($response));

        // Test an "token" create success
        $controller->login($request);

        // test an "token" create fial
        $controller->login($request);
    }

    /**
     * Test AuthController::logout method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testLogout()
    {
        // Create a JWTGuard::class mock
        $guard = $this->createMock(JWTGuard::class);

        // Create a AuthController::class mock
        $controller = $this->getMockBuilder(AuthController::class)
            ->setMethods(['guard', 'response'])
            ->getMock();

        // Create a JsonResponse::class mock.
        $response = $this->getMockBuilder(JsonResponse::class)
            ->setMethods(['json'])
            ->getMock();

        // Mock JsonResponse::json method
        $json = ['message' => '退出成功'];
        $response->expects($this->exactly(1))
            ->method('json')
            ->with($this->equalTo($json))
            ->will($this->returnSelf());

        // Mock AuthController::guard method
        $controller->expects($this->exactly(1))
            ->method('guard')
            ->will($this->returnValue($guard));

        // Mock AuthController::response method
        $controller->expects($this->exactly(1))
            ->method('response')
            ->will($this->returnValue($response));

        // Start the Test
        $controller->logout();
    }

    /**
     * Test AuthController::refresh and respondWithToken method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testRefreshAndTestRespondWithToken()
    {
        $token = \Auth::guard('api')->login($this->user);
        $response = $this->getJson('/api/v2/auth/refresh', [
            'Authorization' => 'Bearer '.$token,
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'refresh_ttl']);
    }
}
