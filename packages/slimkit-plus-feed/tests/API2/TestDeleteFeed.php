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
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class TestDeleteFeed extends TestCase
{
    use DatabaseTransactions;

    private $apiUrl = '/api/v2/feeds';

    /**
     * 前置条件.
     */
    public function setUp()
    {
        parent::setUp();

        $jwtAuthToken = $this->app->make(JWTAuthToken::class);

        $this->user = factory(User::class)->create();

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

        $this->feed = $this->post($this->apiUrl . '?token=' . $this->token, $data)->json();
    }

    public function testDeleteFeed()
    {
        $res = $this->delete($this->apiUrl . "/{$this->feed['id']}?token=" . $this->token);
        $res->assertStatus(204);
    }
}
