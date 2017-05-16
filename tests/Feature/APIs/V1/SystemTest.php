<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SystemTest extends TestCase
{
    use WithoutMiddleware,DatabaseTransactions;

    /**
     * test get component install status.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetComponentStatus()
    {
        $response = $this->json('GET', '/api/v1/system/component/status');

        $response->assertStatus(200);
    }

    /**
     * test get component config.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetComponentConfig()
    {
        $response = $this->json('GET', '/api/v1/system/component/configs', [
            'component' => 'im',
        ]);

        $response->assertStatus(200);
    }

    /**
     * test get create feedback.
     *
     * @author bs<414606094@qq.com>
     */
    public function testCreateFeedback()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/v1/system/feedback', [
                'content' => '123456',
            ]);

        $response->assertStatus(201);
    }

    /**
     * test get user`s conversations.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetConversations()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/v1/system/conversations', [
                'max_id' => 0,
                'limit' => 15,
            ]);

        $response->assertStatus(200);
    }
}
