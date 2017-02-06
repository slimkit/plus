<?php

namespace Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\plus\Models\StorageTask;
use Zhiyi\Plus\Models\User;

class StorageTaskNoticeTest extends TestCase
{
    protected $uri = '/api/v1/storages/task/{storage_task_id}';

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

        // set error Task
        $this->errortask = new StorageTask();
        $this->errortask->hash = 'a9e056d78546f7db451231239161fbf0879';
        $this->errortask->origin_filename = 'teststorage2.jpg';
        $this->errortask->filename = '2017/02/06/0612/a9e056d78546f7db4576112312f0879.jpg';
        $this->errortask->save();

        // set Task
        $this->task = new StorageTask();
        $this->task->hash = 'a9e056d78546f7db457619161fbf0878';
        $this->task->origin_filename = 'teststorage.jpg';
        $this->task->filename = '2017/02/06/0612/a9e056d78546f7db457619161fbf0878.jpg';
        $this->task->save();
    }

    /**
     * 清除测试数据.
     *
     * @author bs<414606094@qq.com>
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->user->forceDelete();
        $this->auth->forceDelete();
        $this->task->forceDelete();
        $this->errortask->forceDelete();
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

        $uri = str_replace('{storage_task_id}', 1, $this->uri);

        $response = $this->patchJson($uri, $requestBody, $requestHeaders);

        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(401);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code'    => 1016,
        ]);
        $response->assertJson($json);
    }

    /**
     * 测试资源不存在的情况.
     *
     * @author bs<414606094@qq.com>
     *
     * @return void
     */
    public function testGetEmptyStorages()
    {
        $requestHeaders = ['ACCESS-TOKEN' => $this->auth->token];
        $requestBody = [];

        $uri = str_replace('{storage_task_id}', 1, $this->uri);

        $response = $this->patchJson($uri, $requestBody, $requestHeaders);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(404);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code'    => 2000,
            'message' => '上传任务不存在',
        ]);
        $response->assertJson($json);
    }

    /**
     * 测试资源参数传入错误的情况.
     *
     * @author bs<414606094@qq.com>
     *
     * @return void
     */
    public function testGetErrorStorages()
    {
        $requestHeaders = ['ACCESS-TOKEN' => $this->auth->token];
        $requestBody = [];

        $uri = str_replace('{storage_task_id}', urlencode('bsbsbs'), $this->uri);

        $response = $this->patchJson($uri, $requestBody, $requestHeaders);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(500);
    }

    /**
     * 测试任务存在文件不存在情况.
     *
     * @author bs<414606094@qq.com>
     *
     * @return void
     */
    public function testGetEmptyFile()
    {
        $requestHeaders = ['ACCESS-TOKEN' => $this->auth->token];
        $requestBody = ['message' => '123'];

        $uri = str_replace('{storage_task_id}', $this->errortask->id, $this->uri);

        $response = $this->patchJson($uri, $requestBody, $requestHeaders);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(500);
    }

    /**
     * 测试正常获取通知情况.
     *
     * @author bs<414606094@qq.com>
     *
     * @return void
     */
    public function testGetNotice()
    {
        $requestHeaders = ['ACCESS-TOKEN' => $this->auth->token];
        $requestBody = ['message' => '123'];
        $uri = str_replace('{storage_task_id}', $this->task->id, $this->uri);

        $response = $this->patchJson($uri, $requestBody, $requestHeaders);
        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(200);
        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'status' => true,
        ]);
        $response->assertJson($json);
    }
}
