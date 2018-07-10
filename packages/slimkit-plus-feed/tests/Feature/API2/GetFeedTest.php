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

class GetFeedTest extends TestCase
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
     * 测试动态列表接口.
     *
     * @return mixed
     */
    public function testGetFeeds()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/feeds');
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['pinned', 'feeds']);
    }

    /**
     * 测试动态详情接口.
     *
     * @return mixed
     */
    public function testGetFeed()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/feeds/'.$this->feed->id);
        $response
            ->assertStatus(200);
    }

    /**
     * 测试未登录获取动态列表接口.
     */
    public function testNotAuthGetFeeds()
    {
        $response = $this->json('GET', '/api/v2/feeds');
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['pinned', 'feeds']);
    }

    /**
     * 测试动态详情接口.
     *
     * @return mixed
     */
    public function testNotAuthGetFeed()
    {
        $response = $this->json('GET', '/api/v2/feeds/'.$this->feed->id);
        $response
            ->assertStatus(200);
    }
}
