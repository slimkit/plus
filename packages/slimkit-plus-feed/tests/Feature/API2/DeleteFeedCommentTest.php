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

class DeleteFeedCommentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create the test need user.
     *
     * @return \Zhiyi\Plus\Models\User
     */
    protected function createUser(): UserModel
    {
        $user = UserModel::factory()->create();
        $ability = AbilityModel::query()->where('name', 'feed-post')->firstOr(function () {
            return AbilityModel::factory()->create([
                'name' => 'feed-post',
            ]);
        });
        $role = RoleModel::query()->where('name', 'test')->firstOr(function () {
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
     */
    protected function addFeed($user)
    {
        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/v2/feeds', [
                'feed_content' => 'test',
                'feed_from' => 5,
                'feed_mark' => (int) (time().rand(1000, 9999)),
            ])
            ->decodeResponseJson();

        return $response['id'];
    }

    /**
     * 添加动态评论测试数据.
     *
     * @param $user
     * @param $feed
     * @return mixed
     *
     * @throws \Exception
     */
    protected function addFeedComment($user, $feed)
    {
        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', "/api/v2/feeds/{$feed}/comments", [
                'body' => 'test',
            ])
            ->decodeResponseJson();

        return $response['comment']['id'];
    }

    /**
     * 删除动态评论.
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function testDeleteFeedComment()
    {
        $user = $this->createUser();
        $feed = $this->addFeed($user);
        $comm = $this->addFeedComment($user, $feed);

        $response = $this
            ->json('DELETE', "/api/v2/feeds/{$feed}/comments/{$comm}");
        $response
            ->assertStatus(204);
    }
}
