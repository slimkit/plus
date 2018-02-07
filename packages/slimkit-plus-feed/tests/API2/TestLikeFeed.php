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

use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestLikeFeed extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds';

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
            'images' => [],
        ];

        $this->feed = $this->post($this->api.'?token='.$this->token, $data)->json();
    }

    public function testLikeFeed()
    {
        // 点喜欢
        $api = $this->api."/{$this->feed['id']}/like?token=".$this->token;
        $res = $this->post($api);

        $this->response = $res->json();
        $res->assertStatus(201);
        $res->assertJsonStructure(['message']);

        // 喜欢该动态的用户列表.
        $api = $this->api."/{$this->feed['id']}/likes?token=".$this->token;
        $res = $this->get($api);
        $res->assertStatus(200);

        // 取消喜欢
        $api = $this->api."/{$this->feed['id']}/unlike?token=".$this->token;
        $res = $this->delete($api);

        $res->assertStatus(204);
    }
}
