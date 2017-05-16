<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use WithoutMiddleware,DatabaseTransactions;

    /**
     * Test get user`s info.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetUserInfo()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/api/v1/users', [
            'user_ids' => [$user->id],
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test reset user`s password.
     *
     * @author bs<414606094@qq.com>
     */
    public function testResetPassword()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/v1/users/password', [
                'new_password' => '123456',
            ]);

        $response->assertStatus(201);

        $this->assertTrue($user->newQuery()->find($user->id)->verifyPassword('123456'));
    }

    /**
     * Test edit user`s profile.
     *
     * @author bs<414606094@qq.com>
     */
    public function testEditProfile()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/v1/users', [
                'intro' => '123456',
            ]);

        $new_user = $user->newQuery()->find($user->id);
        $intro = $new_user->datas->where('profile', 'intro')->first()->pivot->user_profile_setting_data;

        $response->assertStatus(201);
        $this->assertEquals('123456', $intro);
    }

    /**
     * Test get diggsrank.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetDiggRank()
    {
        $response = $this->json('GET', '/api/v1/diggsrank', [
            'limit' => 15,
            'page' => 1,
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test get user`s comments.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetMyComments()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/v1/users/mycomments', [
                'max_id' => 0,
                'limit' => 15,
            ]);

        $response->assertStatus(200);
    }

    /**
     * Test get user`s diggs.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetMyDiggs()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/v1/users/mydiggs', [
                'max_id' => 0,
                'limit' => 15,
            ]);

        $response->assertStatus(200);
    }

    /**
     * Test get user`s new messages.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetMyNewsMessages()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/v1/users/mydiggs', [
                'key' => 'diggs,follows,comments,notices',
            ]);

        $response->assertStatus(200);
    }
}
