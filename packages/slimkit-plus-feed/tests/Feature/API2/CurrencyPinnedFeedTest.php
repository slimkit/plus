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

class CurrencyPinnedFeedTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $feed;

    public function setUp()
    {
        parent::setUp();

        $this->user = UserModel::factory()->create([
            'password' => bcrypt('123456'),
        ]);

        $this->feed = Feed::factory()->create([
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
            ->json('POST', "/api/v2/feeds/{$this->feed->id}/currency-pinneds", [
                'password' => '123456',
            ]);
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
                'password' => '123456',
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
                'password' => '123456',
            ]);
        $response
            ->assertStatus(201);
    }
}
