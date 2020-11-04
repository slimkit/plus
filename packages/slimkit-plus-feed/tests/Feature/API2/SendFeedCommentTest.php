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

namespace SlimKit\PlusFeed\Tests\Feature\API2;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Models\Ability as AbilityModel;
use Zhiyi\Plus\Models\Role as RoleModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

class SendFeedCommentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 创建所需用户.
     *
     * @return UserModel
     */
    protected function createUser(): UserModel
    {
        $user = UserModel::factory()->create();
        $ability = AbilityModel::query()->where('name', 'feed-post')->firstOr(function () {
            return AbilityModel::factory()->create([
                'name' => 'feed-post',
            ]);
        });
        $role = RoleModel::where('name', 'test')->firstOr(function () {
            return RoleModel::factory()->create([
                'name' => 'test',
            ]);
        });
        $role
            ->abilities()
            ->sync($ability);
        $user->roles()->sync($role);

        return $user;
    }

    /**
     * 添加测试动态.
     *
     * @param $user
     * @return mixed
     * @throws \Exception
     */
    protected function addFeed($user)
    {
        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/v2/feeds', [
                'feed_content' => 'test',
                'feed_from' => 5,
                'feed_mark' => intval(time().rand(1000, 9999)),
            ])
            ->decodeResponseJson();

        return $response['id'];
    }

    /**
     * 测试评论动态.
     *
     * @return mixed
     */
    public function testSendFeedComment()
    {
        $user = $this->createUser();
        $feed = $this->addFeed($user);

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', "/api/v2/feeds/{$feed}/comments", [
                'body' => 'test',
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'comment']);
    }

    /**
     * 测试回复评论动态.
     *
     * @return mixed
     */
    public function testReplyFeedComment()
    {
        $owner = $this->createUser();
        $other = $this->createUser();
        $feed = $this->addFeed($owner);

        $response = $this
            ->actingAs($owner, 'api')
            ->json('POST', "/api/v2/feeds/{$feed}/comments", [
                'body' => 'test',
                'reply_user' => $other->id,
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'comment']);
    }
}
