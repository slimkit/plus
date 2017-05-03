<?php

namespace Zhiyi\Plus\Unit\Storages;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Storages\Storage;
use Zhiyi\Plus\Services\Storage as StorageService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Interfaces\Storage\StorageEngineInterface;
use Zhiyi\Plus\Storages\StorageTaskResponse;

class StorageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 前置操作.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setUp()
    {
        parent::setUp();

        // mock \Zhiyi\Plus\Services\Storage
        $storageService = $this->createMock(StorageService::class);

        // mock TestEngine
        $testEngine = $this->createMock(StorageEngineInterface::class);

        // set GetRngines method.
        $storageService->method('getEngines')
            ->will($this->returnValue([
                'test' => [
                    'name' => 'Testing',
                    'engine' => 'TestEngine',
                    'option' => [],
                ]
            ]));

        $this->instance('TestEngine', $testEngine);
        $this->instance(StorageService::class, $storageService);
    }

    /**
     * Tesn engine method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testEngine()
    {
        $storage = new Storage($this->app->make(StorageService::class));

        $this->assertInstanceOf(StorageEngineInterface::class, $storage->engine('test'));
    }

    /**
     * Test getAlise methos.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGetAlise()
    {
        $storage = new Storage($this->app->make(StorageService::class));

        $this->assertEquals('TestEngine', $storage->getAlise('test'));
    }

    /**
     * Test createStorageTask method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testCeateStorageTaskByNoHash()
    {
        $storage = new Storage($this->app->make(StorageService::class));

        $user = factory(User::class)->create();
        $task = factory(StorageTask::class)->make();

        // mock StorageTaskResponse
        $response = $this->createMock(StorageTaskResponse::class);
        $response->method('toArray')
            ->will($this->returnValue(['test' => true]));


        $this->app->make('TestEngine')->method('createStorageTask')
            ->will($this->returnValue($response));

        $data = $storage->createStorageTask($user, $task->origin_filename, $task->hash, $task->mime_type, 120.00, 120.00, 'test');

        $this->assertTrue($data['test']);
    }
}
