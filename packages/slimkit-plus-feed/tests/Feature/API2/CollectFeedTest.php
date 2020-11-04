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

class CollectFeedTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $feed;

    public function setUp()
    {
        parent::setUp();

        $this->user = UserModel::factory()->create();

        $this->feed = Feed::factory()->create([
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
