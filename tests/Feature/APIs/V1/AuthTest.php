<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Http\Controllers\APIs\V1\AuthController;

class AuthTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

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

    /**
     * Test register.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testRegister()
    {
        // created register method.
        $this->app->make(AuthController::class)->method('register')
            ->will($this->returnValue(['status' => true]));

        $response = $this->json('POST', '/api/v1/auth/register');

        $response->assertExactJson(['status' => true]);
    }

    /**
     * Test reset token.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testResetToken()
    {
        // create reset token method.
        $this->app->make(AuthController::class)->method('resetToken')
            ->will($this->returnValue(['status' => true]));

        $response = $this->json('PATCH', '/api/v1/auth');

        $response->assertExactJson(['status' => true]);
    }

    /**
     * 测试找回密码.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testForgotPassword()
    {
        $user = factory(User::class)->make();

        $this->app->make(AuthController::class)->method('forgotPassword')
            ->will($this->returnCallback(function () use ($user) {
                return response()->json($user)->setStatusCode(201);
            }));

        $response = $this->json('patch', '/api/v1/auth/forgot');

        $response->assertStatus(201)
            ->assertJson($user->toArray());
    }
}
