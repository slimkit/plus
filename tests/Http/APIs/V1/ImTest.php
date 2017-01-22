<?php

namespace Ts\Test\Http\APIs\V1;

use App\Models\AuthToken;
use App\Models\User;

class ImTest extends TestCase
{
    protected $uri = '/api/v1/im';
    protected $user;
    protected $auth;

    /**
     * 入口.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-22T10:44:33+080
     *
     * @version  1.0
     */
    protected function setUp()
    {
        parent::setUp();
        $this->createTempData();
    }

    /**
     * 卸载方法，清理测试后的冗余数据.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-22T10:20:04+080
     *
     * @version  1.0
     */
    protected function tearDown()
    {
        foreach ($this->user as $v) {
            $v->forceDelete();
        }
        foreach ($this->auth as $v) {
            $v->forceDelete();
        }
        parent::tearDown();
    }

    /**
     * 创建测试的临时数据.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-22T15:43:42+080
     *
     * @version  1.0
     */
    protected function createTempData()
    {
        $user = User::create([
            'phone' => '1520'.rand(1111111, 9999999),
            'name' => 'ts_用户'.date('YmdHis'),
            'password' => bcrypt(123456),
        ]);

        $auth = new AuthToken();
        $auth->token = md5(str_random(32));
        $auth->refresh_token = md5(str_random(32));
        $auth->expires = 0;
        $auth->state = 1;
        $this->auth[] = $auth;
        $user->tokens()->save($auth);
        $this->user[] = $user;
    }
    /**
     * 测试获取用户授权.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-22T16:06:55+080
     *
     * @version  1.0
     */
    public function testGetAuth()
    {

        // 取出第一个用户授权
        $auth = $this->auth[0];
        $this->get($this->uri.'/users', [
            'ACCESS-TOKEN' => $auth->token,
        ]);
        // 测试http_code
        $this->seeStatusCode(200);
    }
}
