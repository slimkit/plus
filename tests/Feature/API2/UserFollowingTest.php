<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Plus\Models\UserFollow as UserFollowModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserFollowingTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $target;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
        $this->target = factory(UserModel::class)->create();
    }

    protected function tearDown()
    {
        $this->user->forceDelete();
        $this->target->forceDelete();

        parent::tearDown();
    }

    public function testAttachFollowingUser()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('PUT', '/api/v2/user/followings/'.$this->target->id);
        $response->assertStatus(204);

        $followingCount = UserCountModel::where('type', 'user-following')
            ->where('user_id', $this->target->id)
            ->first();

        $this->assertFalse(is_null($followingCount));
        $this->assertTrue($followingCount->total === 1);

        $following = UserFollowModel::where('user_id', $this->user->id)
            ->where('target', $this->target->id)
            ->first();

        $this->assertFalse(is_null($following));
    }

    public function testDetachFollowingUser()
    {
        $this->user->followings()->attach($this->target);
        $this->user->extra()->firstOrCreate([])->increment('followings_count', 1);
        $this->target->extra()->firstOrCreate([])->increment('followers_count', 1);

        $followingCount = new UserCountModel();
        $followingCount->user_id = $this->target->id;
        $followingCount->type = 'user-following';
        $followingCount->total = 1;
        $followingCount->save();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v2/user/followings/'.$this->target->id);
        $response->assertStatus(204);

        $followingCount = UserCountModel::where('type', 'user-following')
            ->where('user_id', $this->target->id)
            ->first();
        $this->assertFalse(is_null($followingCount));
        $this->assertTrue($followingCount->total === 0);

        $following = UserFollowModel::where('user_id', $this->user->id)
            ->where('target', $this->target->id)
            ->first();
        $this->assertTrue(is_null($following));
    }
}
