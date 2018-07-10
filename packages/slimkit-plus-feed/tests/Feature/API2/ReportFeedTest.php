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

namespace SlimKit\PlusFeed\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\Role as RoleModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\Ability as AbilityModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportFeedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create the test need user.
     *
     * @return \Zhiyi\Plus\Models\User
     */
    protected function createUser(): UserModel
    {
        $user = factory(UserModel::class)->create();
        $ability = AbilityModel::where('name', 'feed-post')->firstOr(function () {
            return factory(AbilityModel::class)->create([
                'name' => 'feed-post',
            ]);
        });
        $role = RoleModel::where('name', 'test')->firstOr(function () {
            return factory(RoleModel::class)->create([
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
                'feed_mark' => intval(time().rand(1000, 9999)),
            ])
            ->decodeResponseJson();

        return $response['id'];
    }

    /**
     * 举报动态测试.
     *
     * @return mixed
     */
    public function testReportFeed()
    {
        $user = $this->createUser();
        $feed = $this->addFeed($this->createUser());

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', "/api/v2/feeds/{$feed}/reports", ['reason' => '测试']);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }
}
