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
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

class DeleteFeedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 测试删除自己动态
     *
     * @return mixed
     */
    public function testDeleteFeed()
    {
        $user = UserModel::factory()->create();
        $feed = Feed::factory()->create([
            'user_id' => $user->id,
        ]);
        $response = $this
            ->actingAs($user, 'api')
            ->json('DELETE', '/api/v2/feeds/'.$feed->id.'/currency');
        $response
            ->assertStatus(204);
    }

    /**
     * 测试删除他人动态.
     *
     * @return mixed
     */
    public function testDeleteOtherFeed()
    {
        $user = UserModel::factory()->create();
        $feed = Feed::factory()->create([
            'user_id' => UserModel::factory()->create()->id,
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('DELETE', '/api/v2/feeds/'.$feed->id.'/currency');
        $response
            ->assertStatus(403);
    }

    /**
     * 删除不存在的动态.
     *
     * @return mixed
     */
    public function testDeleteNonExistFeed()
    {
        $response = $this
            ->actingAs(UserModel::factory()->create(), 'api')
            ->json('DELETE', '/api/v2/feeds/0/currency');
        $response
            ->assertStatus(404);
    }
}
