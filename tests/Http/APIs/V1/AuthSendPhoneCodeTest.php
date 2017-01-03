<?php

namespace Ts\Test\Http\APIs\V1;

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
     * Message code: 1000.
     *
     * @return [type] [description]
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
        $this->postJson($this->uri, $requestBody);

        // Asserts that the status code of the response matches the given code.
        $this->seeStatusCode(403);

        // Assert that the response contains an exact JSON array.
        $json = $this->createMessageResponseBody([
            'code' => 1000,
        ]);
        $this->seeJsonEquals($json);
    }
}
