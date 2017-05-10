<?php

namespace Zhiyi\Plus\Unit\Middleware\Auth;

use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Http\Middleware\VerifyPhoneNumber;

class VerifyPhoneNumberTest extends TestCase
{
	/**
	 * test verify phone number
	 *
	 * @author bs<414606094@qq.com>
	 */
	public function testHandle()
	{
		$request = $this->getMockBuilder(Request::class)
			->setMethods(['all'])
			->getMock();

		$request->expects($this->any())
			->method('all')
			->willReturn([
				'phone' => '',
			]);

		$response = TestResponse::fromBaseResponse(
            with(new VerifyPhoneNumber())->handle($request, function () {})
        );

        $response->assertStatus(403);

	}
}