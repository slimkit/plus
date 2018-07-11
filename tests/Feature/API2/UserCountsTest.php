<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;

class UserCountsTest extends TestCase
{
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
    }

    protected function tearDown()
    {
        $this->user->forceDelete();

        parent::tearDown();
    }

    public function testUserCounts()
    {
        $followingCount = new UserCountModel();
        $followingCount->user_id = $this->user->id;
        $followingCount->type = 'user-following';
        $followingCount->total = 1;
        $followingCount->save();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/user/counts');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => [
                'following',
            ],
        ]);

        $this->assertEquals(1, $response->decodeResponseJson('user.following'));

        $followingCount = UserCountModel::where('type', 'user-following')
            ->where('user_id', $this->user->id)
            ->first();

        $this->assertNotNull($followingCount);
        $this->assertEquals(1, $followingCount->total);
    }
}
