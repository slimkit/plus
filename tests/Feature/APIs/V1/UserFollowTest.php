<?php

namespace Zhiyi\Plus\Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserFollowTest extends TestCase
{
    use WithoutMiddleware,DatabaseTransactions;

    /**
     * test follow user.
     *
     * @author bs<414606094@qq.com>
     */
    public function testUserFollow()
    {
        $user = factory(User::class)->create();
        $followed_user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/v1/users/follow', [
                'user_id' => $followed_user->id,
            ]);

        $response->assertStatus(201);
    }

    /**
     * test cancel follow.
     *
     * @author bs<414606094@qq.com>
     */
    public function testCancelFollow()
    {
        $user = factory(User::class)->create();
        $followed_user = factory(User::class)->create();

        $follow = $this->actingAs($user, 'api')
            ->json('POST', '/api/v1/users/follow', [
                'user_id' => $followed_user->id,
            ]);

        $follow->assertStatus(201);

        $unfollow = $this->actingAs($user, 'api')
            ->json('DELETE', '/api/v1/users/unFollow', [
                'user_id' => $followed_user->id,
            ]);

        $unfollow->assertStatus(204);
    }

    /**
     * test get follow users.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetFollowUsers()
    {
        $user = factory(User::class)->create();

        $response = $this->json('GET', '/api/v1/follows/follows/'.$user->id, [
            'limit' => 15,
        ]);

        $response->assertStatus(200);
    }

    /**
     * test get followed users.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetFollowedUsers()
    {
        $user = factory(User::class)->create();

        $response = $this->json('GET', '/api/v1/follows/followeds/'.$user->id, [
            'limit' => 15,
        ]);

        $response->assertStatus(200);
    }

    /**
     * test get user follow status.
     *
     * @author bs<414606094@qq.com>
     */
    public function testGetFollowStatus()
    {
        $user = factory(User::class)->create();
        $object_user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/v1/follows/followeds/'.$user->id, [
            'user_ids' => $object_user->id,
            ]);

        $response->assertStatus(200);
    }
}
