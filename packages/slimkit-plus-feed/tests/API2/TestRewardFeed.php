<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Tests\API2;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Auth\JWTAuthToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestRewardFeed extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds';

    public function setUp()
    {
        parent::setUp();

        $jwtAuthToken = $this->app->make(JWTAuthToken::class);

        $this->rewardUser = factory(User::class)->create();
        $this->rewardUser->roles()->sync([2]);
        $this->rewardUser->wallet()->firstOrCreate([])->increment('balance', 1000000);
        $this->rewardToken = $jwtAuthToken->create($this->rewardUser);

        $this->user = factory(User::class)->create();
        $this->user->roles()->sync([2]);
        $this->user->wallet()->firstOrCreate([])->increment('balance', 1000000);
        $this->token = $jwtAuthToken->create($this->user);

        $this->feed = $this->post($this->api.'?token='.$this->rewardToken, [
            'feed_content' => '单元测试动态数据',
            'feed_from' => 1,
            'feed_mark' => time(),
            'feed_latitude' => '',
            'feed_longtitude' => '',
            'feed_geohash' => '',
            'amount' => 0,
            'images' => [],
        ])->json();
    }

    public function testRewardFeed()
    {
        // 打赏用户动态.
        $api = $this->api."/{$this->feed['id']}/rewards?token=".$this->rewardToken;
        $res = $this->post($api, ['amount' => 50]);

        $res->assertStatus(201)->assertJsonStructure(['message']);
    }
}
