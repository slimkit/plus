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
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class TestCollectFeed extends TestCase
{
    use DatabaseTransactions;

    protected $api = '/api/v2/feeds';

    protected $user;

    /**
     * 前置条件.
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->user->roles()->sync([2]);
    }

    public function testCollectFeed()
    {
        $token = $this->guard()->login($this->user);

        $this->seedTestFeed($token);

        // 动态收藏接口: POST /feeds/:feed/collections
        $res = $this->post($this->api."/{$this->feed['id']}/collections?token=".$token);
        $res->assertStatus(201)
            ->assertJsonStructure(['message']);

        // 动态收藏列表: GET /feeds/:feed/collections
        $res = $this->get($this->api.'/collections?token='.$token)
            ->assertStatus(200)
            ->assertJsonStructure([
                ['id', 'user_id', 'feed_content', 'feed_from', 'like_count', 'feed_view_count', 'feed_comment_count', 'feed_latitude', 'feed_longtitude', 'feed_geohash', 'audit_status', 'feed_mark', 'created_at', 'updated_at', 'deleted_at', 'paid_node', 'comments', 'has_collect', 'has_like', 'images', 'user'],
            ]);

        // 取消动态收藏: DELETE /feeds/:feed/uncollectD
        $res = $this->delete($this->api."/{$this->feed['id']}/uncollect?token=".$token)
            ->assertStatus(204);
    }

    protected function seedTestFeed($token)
    {
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

        $this->feed = $this->post($this->api.'?token='.$token, $data)->json();
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard(): Guard
    {
        return Auth::guard('api');
    }
}
