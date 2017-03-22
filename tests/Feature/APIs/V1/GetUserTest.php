<?php

namespace Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\AuthToken;

class GetUserTest extends TestCase
{
    protected $uri_template = '/api/v1/users';

    protected $uri;
    protected $user;
    protected $auth;

    protected function setUp()
    {
        parent::setUp();

        // register user.
        $this->user = User::create([
            'phone'    => '1878'.rand(1111111, 9999999),
            'name'     => 'ts'.rand(1000, 9999),
            'password' => bcrypt(123456),
        ]);

        // set token info.
        $this->auth = new AuthToken();
        $this->auth->token = md5(str_random(32));
        $this->auth->refresh_token = md5(str_random(32));
        $this->auth->expires = 0;
        $this->auth->state = 1;

        // save token.
        $this->user->tokens()->save($this->auth);

        // set uri.
        $this->uri = $this->uri_template;
    }

    /**
     * 卸载方法，清理测试后的冗余数据.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function tearDown()
    {
        $this->user->forceDelete();
        $this->auth->forceDelete();
        parent::tearDown();
    }

    /**
     * 没有找到用户 (获取不存在的用户).
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testNotFoundUser()
    {
        $uri = $this->uri;
        $response = $this->post($uri, [
            'user_ids' => [9999999],
        ],
        [
            'ACCESS-TOKEN' => $this->auth->token,
        ]);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(404);

        $json = static::createJsonData([
            'status'  => false,
            'code'    => 1019,
            'message' => '没有相关用户',
            'data'    => null,

        ]);
        $response->assertJson($json);
    }

    public function testGetUserData()
    {
        $response = $this->post($this->uri, [
            'user_ids' => [$this->user->id],
        ],
        [
            'ACCESS-TOKEN' => $this->auth->token,
        ]
        );

        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(201);

        // $datas = $this->user->toArray();
        // $content = $response->getContent();
        $json = static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => [$this->user->toArray()],
        ]);
        $response->assertJson($json);
    }
}
