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

class CollectFeedTest extends TestCase
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
     * 收藏动态.
     *
     * @return mixed
     */
    public function testLikeCollect()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/feeds/{$this->feed->id}/collections");

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 收藏列表.
     *
     * @return mixed
     */
    public function testGetFeedCollections()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/feeds/collections');

        $response
            ->assertStatus(200);
    }

    /**
     * 取消收藏.
     *
     * @return mixed
     */
    public function testUnCollectFeed()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('DELETE', "/api/v2/feeds/{$this->feed->id}/uncollect");

        $response
            ->assertStatus(204);
    }
}
