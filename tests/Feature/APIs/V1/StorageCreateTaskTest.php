<?php

namespace Tests\Feature\APIs\V1;

use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\Assert as PHPUnit;
use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\plus\Models\StorageTask;
use Zhiyi\Plus\Models\User;

class StorageCreateTaskTest extends TestCase
{
    protected $uri = '/api/v1/storages/task/{hash}/{origin_filename}';
    protected $filename = __DIR__.'/res/teststorage.jpg';

    protected $phone = '18781993582';
    protected $password = 123456;

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

        $uri = $this->replaceUri();
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
        $uri = $this->replaceUri()['uri'];

        $response = $this->postJson($uri, $requestBody, $requestHeaders);

        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(401);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code'    => 1016,
        ]);
        $response->assertJson($json);
    }

    /**
     * 测试秒传情况.
     *
     * @author bs<414606094@qq.com>
     *
     * @return void
     */
    public function testFlashUpload()
    {
        $requestHeaders = ['ACCESS-TOKEN' => $this->auth->token];
        $requestBody = [];
        $uri = $this->replaceUri()['uri'];

        $response = $this->postJson($uri, $requestBody, $requestHeaders);
        $response->assertStatus(201);

        $taskId = $response->original['data']['storage_task_id'];
        $responseHash = StorageTask::find($taskId)->hash;

        $bool = $this->replaceUri()['hash'] === $responseHash;

        PHPUnit::assertTrue($bool);
    }

    /**
     * 测试正常创建上传队列情况.
     *
     * @author bs<414606094@qq.com>
     *
     * @return void
     */
    public function testCreateTask()
    {
        $requestBody = [];
        $requestHeaders = ['ACCESS-TOKEN' => $this->auth->token];
        $uri = $this->replaceUri()['uri'];

        $response = $this->postJson($uri, $requestBody, $requestHeaders);

        $response->assertStatus(201);
    }

    /**
     * 根据默认测试文件生成测试链接.
     *
     * @author bs<414606094@qq.com>
     *
     * @return array
     */
    protected function replaceUri()
    {
        $filename = $this->filename;
        $fileHash = md5_file($filename);
        $basename = with(new Filesystem())->basename($filename);

        $uri = str_replace('{hash}', urlencode($fileHash), $this->uri);
        $uri = str_replace('{origin_filename}', urlencode($basename), $uri);

        return ['uri' => $uri, 'hash' => $fileHash, 'name' => $filename];
    }
}
