<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Zhiyi\Plus\Http\Controllers\APIs\V1\AuthController;

class AuthSendPhoneCodeTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Test send phone code.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSendPhoneCode()
    {
        // json
        $json = ['status' => true];

        // mock controller.
        $controller = $this->getMockBuilder(AuthController::class)
            ->setMethods(['sendPhoneCode'])
            ->getMock();

        $controller->method('sendPhoneCode')
            ->will($this->returnValue($json));

        $this->instance(AuthController::class, $controller);

        // request.
        $response = $this->json('POST', 'api/v1/auth/phone/send-code');

        // assert.
        $response->assertStatus(200)
            ->assertExactJson($json);
    }
}
