<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\AuthToken;
use Illuminate\Http\UploadedFile;
use Zhiyi\Plus\Models\StorageTask;
use Illuminate\Support\Facades\Storage;
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
        // $user = factory(User::class)->create();

        // $response = $this->actingAs($user, 'api')
        //     ->json('POST', '/api/v1/users', [
        //         'user_ids' => [$user->id]
        //     ]);

        // $json = $response->json();

        // $response->assertStatus(201);
        // $this->assertEquals($user->id, $json['data'][0]['id']);
        //

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
        $task = factory(StorageTask::class)->create();

        Storage::fake('public');

        $filesystem = $this->app->make('files');
        $path = $filesystem->dirname($task->filename);
        $name = $filesystem->basename($task->filename);

        UploadedFile::fake()
            ->image($task->filename, $task->width, $task->height)
            ->storePubliclyAs($path, $name, 'public');

        $response = $this->json('PATCH', '/api/v1/storages/task/'.$task->id, [
            'message' => 'xxx',
        ]);

        $response->assertStatus(201);
    }
}
