<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\AuthToken;
use Illuminate\Http\UploadedFile;
use Zhiyi\Plus\Models\StorageTask;
use Illuminate\Support\Facades\Storage;
use Zhiyi\Plus\Models\Storage as StorageModel;
use Zhiyi\Plus\Services\Storage as StorageService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StorageTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    /**
     * Test get User info.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testCreateStorageTask()
    {
        $user = factory(User::class)->create();
        $user->tokens()->save(
            factory(AuthToken::class)->make()
        );

        // set storage engine.
        app(StorageService::class)->setEngineSelect('local');

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/v1/storages/task', [
                'hash' => 'xxxxxxx',
                'origin_filename' => 'xxx.jpg',
                'mime_type' => 'image/jpeg',
            ]);

        $response->assertStatus(201);
    }

    /**
     * 测试储存任务通知.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testStorageTaskNotice()
    {
        $task = factory(StorageTask::class)->create([
            'width' => 120,
            'height' => 120,
        ]);
        $user = factory(User::class)->create();

        Storage::fake('public');

        $filesystem = $this->app->make('files');
        $path = $filesystem->dirname($task->filename);
        $name = $filesystem->basename($task->filename);

        UploadedFile::fake()
            ->image($task->filename, $task->width, $task->height)
            ->storePubliclyAs($path, $name, 'public');

        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/v1/storages/task/'.$task->id, [
                'message' => 'xxx',
            ]);

        $response->assertStatus(201);
    }

    /**
     * Test delete storage task.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testStorageTaskDelete()
    {
        $task = factory(StorageTask::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', '/api/v1/storages/task/'.$task->id);

        $response->assertStatus(200);
    }

    /**
     * 测试图片跳转.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testResourceRedirect()
    {
        Storage::fake('public');

        $storage = factory(StorageModel::class)->create();
        $this->instance(StorageModel::class, $storage);

        $response = $this->get('/api/v1/storages/100');

        $response->assertRedirect(
            Storage::url($storage->filename)
        );
    }

    /**
     * Test storage Links API.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testStorageLinks()
    {
        Storage::fake('public');

        $storage = factory(StorageModel::class)->create();

        $response = $this->json('GET', '/api/v1/storages?id='.$storage->id);

        $response->assertSuccessful();
    }

    /**
     * Test local file upload.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testLocalFileUpload()
    {
        Storage::fake('public');

        $user = factory(User::class)->create();
        $task = factory(StorageTask::class)->create();

        $response = $this->actingAs($user, 'api')
            ->post('/api/v1/storages/task/'.$task->id, [
                'file' => UploadedFile::fake()->image($task->origin_filename),
            ]);

        $response->assertStatus(200);
        Storage::disk('public')->assertExists($task->filename);
    }
}
