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

class LikeFeedTest extends TestCase
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
     * 给动态点赞.
     *
     * @return mixed
     */
    public function testLikeFeed()
    {
        $response = $this

            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/feeds/{$this->feed->id}/like");
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 喜欢的人列表.
     *
     * @return mixed
     */
    public function testGetFeedLikePerson()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', "/api/v2/feeds/{$this->feed->id}/likes");
        $response
            ->assertStatus(200);
    }

    /**
     * 取消点赞.
     *
     * @return mixed
     */
    public function testUnLikeFeed()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('DELETE', "/api/v2/feeds/{$this->feed->id}/unlike");
        $response
            ->assertStatus(204);
    }
}
