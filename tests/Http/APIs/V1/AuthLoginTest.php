<?php

namespace Ts\Test\Http\APIs\V1;

use App\Models\User;

class AuthLoginTest extends TestCase
{
    protected static $uri = '/api/v1/auth';

    protected static $user;

    protected static $phone;
    protected static $name;
    protected static $password;

    /**
     * 前置操作,注册用户.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function setUp()
    {
        parent::setUp();

        static::$phone = '187819'.rand(11111, 99999);
        static::$name = 'ts'.rand(10000, 99999);
        static::$password = '123456';

        static::$user = new User();
        static::$user->phone = static::$phone;
        static::$user->name = static::$name;
        static::$user->password = bcrypt(static::$password);
        static::$user->save();
    }

    /**
     * 卸载方法，清理测试后的冗余数据.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function tearDown()
    {
        static::$user->forceDelete();
        parent::tearDown();
    }

    /**
     * 测试无设备号的登录情况.
     * code: 1014.
     *
     * @return [type] [description]
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testNotExisteDeviceCode()
    {
        $requestBody = [
            'phone'    => static::$phone,
            'password' => static::$password,
        ];

        $this->postJson(static::$uri, $requestBody);

        // Asserts that the status code of the response matches the given code.
        $this->seeStatusCode(422);

        // Assert that the response contains an exact JSON array.
        $json = $this->createMessageResponseBody([
            'code'    => 1014,
            'message' => '设备号不能为空',
        ]);
        $this->seeJsonEquals($json);
    }

    public function testNotExistePhone()
    {
        $this->postJson(static::$uri, [
            'pasword'     => static::$password,
            'device_code' => 'The is device code.',
        ]);

        // Asserts that the status code of the response matches the given code.
        $this->seeStatusCode(403);

        // Assert that the response contains an exact JSON array.
        $json = $this->createMessageResponseBody([
            'code' => 1000,
        ]);
        $this->seeJsonEquals($json);
    }
}
