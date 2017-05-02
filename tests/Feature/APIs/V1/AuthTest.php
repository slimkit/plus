<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Zhiyi\Plus\Http\Controllers\APIs\V1\AuthController;

class AuthTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * 前置操作.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setUp()
    {
        parent::setUp();

        // mock controller.
        $controller = $this->getMockBuilder(AuthController::class)
            ->setMethods(['login', 'resetToken', 'register', 'forgotPassword'])
            ->getMock();

        $this->instance(AuthController::class, $controller);
    }

    /**
     * Test login api.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testLogin()
    {
        // create login method.
        $this->app->make(AuthController::class)->method('login')
            ->will($this->returnCallback(function (Request $request) {
                return response()->json([
                    'phone' => $request->input('phone'),
                    'password' => $request->input('password'),
                ])->setStatusCode(201);
            }));

        // create data.
        $data = [
            'phone' => '17000000000',
            'password' => '123456',
        ];

        // request
        $response = $this->json('POST', 'api/v1/auth', $data);

        // Assert
        $response->assertStatus(201)
            ->assertExactJson($data);
    }
}
