<?php

namespace Zhiyi\Plus\Unit\Storages;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Storages\Storage;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Plus\Storages\StorageTaskResponse;
use Zhiyi\Plus\Models\Storage as StorageModel;
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\Plus\Services\Storage as StorageService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Interfaces\Storage\StorageEngineInterface;

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
                ],
            ]));

        $this->instance('TestEngine', $testEngine);
        $this->instance(StorageService::class, $storageService);
        $this->instance(Storage::class, new Storage(
            $this->app->make(StorageService::class)
        ));
    }

    /**
     * Tesn engine method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testEngine()
    {
        $this->assertInstanceOf(
            StorageEngineInterface::class,
            $this->app->make(Storage::class)->engine('test')
        );
    }

    /**
     * Test getAlise methos.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testGetAlise()
    {
        $this->assertEquals(
            'TestEngine',
            $this->app->make(Storage::class)->getAlise('test')
        );
    }

    /**
     * Test createStorageTask method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testCreateStorageTaskByNoHash()
    {
        $user = factory(User::class)->create();
        $task = factory(StorageTask::class)->make();

        // mock StorageTaskResponse
        $response = $this->createMock(StorageTaskResponse::class);
        $response->method('toArray')
            ->will($this->returnValue(['test' => true]));

        $this->app->make('TestEngine')->method('createStorageTask')
            ->will($this->returnValue($response));

        $data = $this->app
            ->make(Storage::class)
            ->createStorageTask($user, $task->origin_filename, $task->hash, $task->mime_type, 120.00, 120.00, 'test');

        $this->assertTrue($data['test']);
    }

    /**
     * Test createStorageTask method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testCreateStorageTask()
    {
        $user = factory(User::class)->create();
        // $task = factory(StorageTask::class)->make();
        $storageData = factory(StorageModel::class)->create([
            'image_width' => 120.00,
            'image_height' => 120.00,
        ]);

        $data = $this->app
            ->make(Storage::class)
            ->createStorageTask($user, $storageData->origin_filename, $storageData->hash, $storageData->mime, $storageData->image_width, $storageData->image_height, 'test');

        $this->assertEquals($storageData->id, $data['storage_id']);
    }

    /**
     * Test notice method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testNotice()
    {
        $task = factory(StorageTask::class)->make([
            'width' => 120.00,
            'height' => 120.00,
        ]);

        $response = TestResponse::fromBaseResponse(
            $this->app->make(Storage::class)->notice('', $task, 'test')
        );

        $response->assertStatus(201);
    }

    /**
     * Test url method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testUrl()
    {
        $task = factory(StorageTask::class)->make();

        $this->app->make('TestEngine')->method('url')
            ->will($this->returnArgument(0));

        $filename = $this->app->make(Storage::class)->url($task->filename, 100, 'test');

        $this->assertEquals($task->filename, $filename);
    }
}
