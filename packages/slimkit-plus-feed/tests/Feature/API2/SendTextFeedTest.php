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

class SendTextFeedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create the test need user.
     *
     * @return \Zhiyi\Plus\Models\User
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createUser(): UserModel
    {
        $user = UserModel::factory()->create();
        $ability = AbilityModel::query()->where('name', 'feed-post')->firstOr(function () {
            return AbilityModel::factory()->create([
                'name' => 'feed-post',
            ]);
        });
        $role = RoleModel::factory()
            ->make([
                'name' => 'test',
            ]);
        $role
            ->abilities()
            ->sync($ability);
        $user->roles()->sync($role);

        return $user;
    }

    /**
     * Test send a public feed.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSendPublic()
    {
        $user = $this->createUser();
        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/v2/feeds', [
                'feed_content' => 'Test send public feed.',
                'feed_from' => 5,
                'feed_mark' => intval(time().rand(1000, 9999)),
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['id', 'message']);
    }

    /**
     * Test not send ability user send feed.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testSendNotSendAbility()
    {
        $user = UserModel::factory()->create();
        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/v2/feeds', []);
        $response->assertStatus(403);
    }
}
