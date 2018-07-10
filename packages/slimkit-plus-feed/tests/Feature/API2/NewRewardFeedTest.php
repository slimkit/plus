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

class NewRewardFeedTest extends TestCase
{
    use DatabaseTransactions;

    protected $owner;

    protected $other;

    protected $feed;

    public function setUp()
    {
        parent::setUp();

        $this->owner = factory(UserModel::class)->create();

        $this->other = factory(UserModel::class)->create();

        $this->feed = factory(Feed::class)->create([
            'user_id' => $this->owner->id,
        ]);
    }

    /**
     * 测试新版打赏接口.
     *
     * @return mixed
     */
    public function testRewardFeed()
    {
        $this->other->currency()->increment('sum', 1000);

        $response = $this
            ->actingAs($this->other, 'api')
            ->json('POST', "/api/v2/feeds/{$this->feed->id}/new-rewards", ['amount' => 10]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }
}
