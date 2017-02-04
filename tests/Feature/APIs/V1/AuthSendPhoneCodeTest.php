<?php

namespace Tests\Feature\APIs\V1;

use PHPUnit\Framework\Assert as PHPUnit;

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

        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(403);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code' => 1000,
        ]);
        $response->assertJson($json);
    }

    /**
     * 测试错误的发送类型返回数据.
     *
     * Message code: 1011
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testErrorSendType()
    {
        $requestBody = [
            'phone' => '18781993583',
        ];

        $response = $this->postJson($this->uri, $requestBody);

        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(403);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code'    => 1011,
            'message' => '类型错误',
        ]);
        $response->assertJson($json);
    }

    /**
     * 测试注册类型的发送.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testRegisterSendType()
    {
        $requestBody = [
            'phone' => '18781993583',
            'type'  => 'register',
        ];

        $response = $this->postJson($this->uri, $requestBody);

        $json = $response->json();
        PHPUnit::assertTrue(is_array($json));
    }

    /**
     * 测试改变类型的发送.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testChangeSendType()
    {
        $requestBody = [
            'phone' => '18781993583',
            'type'  => 'change',
        ];

        $response = $this->postJson($this->uri, $requestBody);

        $json = $response->json();
        PHPUnit::assertTrue(is_array($json));
    }
}
