<?php

namespace Tests\Feature\APIs\V1;

class AuthSendPhoneCodeTest extends TestCase
{
    /**
     * API uri.
     *
     * @var string
     */
    protected $uri = '/api/v1/auth/phone/send-code';

    /**
     * 测试错误的请求手机号验证.
     *
     * Message code: 1000
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testPostErrorPhoneNumber()
    {
        // create request data.
        $requestBody = [
            'phone' => '*****',
        ];

        // request api, send data.
        $response = $this->postJson($this->uri, $requestBody);

        // var_dump($response);

        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(403);

        // // Assert that the response contains an exact JSON array.
        // $json = $this->createMessageResponseBody([
        //     'code' => 1000,
        // ]);
        // $response->seeJsonEquals($json);
    }
}
