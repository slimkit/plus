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

class CurrencyPinnedFeedTest extends TestCase
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
     * 不传置顶天数和置顶金额.
     *
     * @return mixed
     */
    public function testNonParamsCurrencyPinnedFeed()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/feeds/{$this->feed->id}/currency-pinneds");
        $response
            ->assertStatus(422);
    }

    /**
     * 余额不足.
     *
     * @return mixed
     */
    public function testNotEnoughCurrencyPinnedFeed()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/feeds/{$this->feed->id}/currency-pinneds", [
                'amount' => 1000,
                'day' => 10,
            ]);
        $response
            ->assertStatus(422);
    }

    /**
     * 置顶动态.
     *
     * @return mixed
     */
    public function testCurrencyPinnedFeed()
    {
        $this->user->currency()->update([
            'sum' => 1000,
            'type' => 1,
        ]);

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/feeds/{$this->feed->id}/currency-pinneds", [
                'amount' => 1000,
                'day' => 10,
            ]);
        $response
            ->assertStatus(201);
    }
}
