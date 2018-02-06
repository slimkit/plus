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
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class TestRankFeed extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds/ranks';

    private $jsonStructure = ['id', 'name', 'sex', 'following', 'follower', 'avatar', 'bg', 'verified', 'extra'];

    /**
     * 前置条件.
     */
    public function setUp()
    {
        parent::setUp();

        $jwtAuthToken = $this->app->make(\Zhiyi\Plus\Auth\JWTAuthToken::class);

        $this->user = factory(\Zhiyi\Plus\Models\User::class)->create();

        $this->user->roles()->sync([2]);

        $this->token = $jwtAuthToken->create($this->user);

        $data = [
            'feed_content' => '单元测试动态数据',
            'feed_from' => 1,
            'feed_mark' => time(),
            'feed_latitude' => '',
            'feed_longtitude' => '',
            'feed_geohash' => '',
            'amount' => 100,
            'images' => []
        ];

        $this->feed = $this->post('/api/v2/feeds' . '?token=' . $this->token, $data)->json();
    }

    public function testFeedRank()
    {
        // 动态日排行: GET /feeds/ranks
        $res = $this->get($this->api, ['type' => 'day']);
        $res->assertStatus(200)->assertJsonStructure([$this->jsonStructure]);

        // 动态周排行: GET /feeds/ranks
        $res = $this->get($this->api, ['type' => 'week']);
        $res->assertStatus(200)->assertJsonStructure([$this->jsonStructure]);

        // 动态月排行: GET /feeds/ranks
        $res = $this->get($this->api, ['type' => 'mouth']);
        $res->assertStatus(200)->assertJsonStructure([$this->jsonStructure]);
    }
}