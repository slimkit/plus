<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Tests\Unit\Http\Controllers\APIs\V2;

use stdClass;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard;
use Zhiyi\Plus\Http\Controllers\APIs\V2\AuthController;

class AuthControllerTest extends TestCase
{
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
            'id' => 1,
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
        // Create a JWTGuard::class mock
        $guard = $this->getMockBuilder(JWTGuard::class)
                      ->disableOriginalConstructor()
                      ->setMethods(['refresh', 'factory'])
                      ->getMock();

        // Create a stdClass::class mock
        $stdClass = $this->getMockBuilder(stdClass::class)
                         ->setMethods(['getTTL'])
                         ->getMock();

        // Create a AuthController::class mock
        $controller = $this->getMockBuilder(AuthController::class)
                           ->setMethods(['guard'])
                           ->getMock();

        // Mock stdClass::getTTL method
        $stdClass->expects($this->exactly(1))
                 ->method('getTTL')
                 ->will($this->returnValue($ttl = 60));

        // Mock JWTGuard::refresh method
        $guard->expects($this->exactly(1))
              ->method('refresh')
              ->will($this->returnValue($token = 'token'));

        // Mock JWTGuard::factory method
        $guard->expects($this->exactly(1))
              ->method('factory')
              ->will($this->returnValue($stdClass));

        // Mock AuthController::guard method
        $controller->expects($this->exactly(2))
                   ->method('guard')
                   ->will($this->returnValue($guard));

        // Start test
        $result = $controller->refresh();

        $this->assertInstanceOf(JsonResponse::class, $result);

        $original = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $ttl,
            'refresh_ttl' => config('jwt.refresh_ttl'),
        ];
        $this->assertEquals($original, $result->getOriginalContent());
    }
}
