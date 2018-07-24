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
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class DeleteFeedTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $feed;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();

        $this->feed = factory(Feed::class)->create([
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * 测试删除自己动态
     *
     * @return mixed
     */
    public function testDeleteFeed()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v2/feeds/'.$this->feed->id);
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
        $response = $this
            ->actingAs(factory(UserModel::class)->create(), 'api')
            ->json('DELETE', '/api/v2/feeds/'.$this->feed->id);
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
            ->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v2/feeds/0');
        $response
            ->assertStatus(404);
    }

    /**
     * 新版删除动态 退还积分.
     *
     * @return mixed
     */
    public function testNewDeleteFeed()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('DELETE', "/api/v2/feeds/{$this->feed->id}/currency");
        $response
            ->assertStatus(204);
    }
}
