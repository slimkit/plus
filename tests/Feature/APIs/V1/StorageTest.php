<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\AuthToken;
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
}
