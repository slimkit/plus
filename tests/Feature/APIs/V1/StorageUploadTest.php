<?php

namespace Tests\Feature\APIs\V1;

use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\Assert as PHPUnit;
use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\plus\Models\StorageTask;
use Zhiyi\Plus\Models\User;

class StorageUploadTest extends TestCase
{
    protected $uri = '/api/v1/storages/task/{storage_task_id}';
    protected $filename = __DIR__.'/res/teststorage.jpg';

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
     * 测试认证不通过的返回情况.
     *
     * test middleware \App\Http\Middleware\AuthUserToken
     *
     * @author bs<414606094@qq.com>
     *
     * @return void
     */
    public function testAuthTokenError()
    {
        $requestHeaders = ['ACCESS-TOKEN' => ''];
        $requestBody = [];

        $filename = $this->filename;
        $uri = $this->uri;

        $response = $this->postJson($uri, $requestBody, $requestHeaders);

        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(401);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code'    => 1016,
        ]);
        $response->assertJson($json);
    }

}
