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
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class PinnedFeedTest extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds';

    /**
     * 接口测试前置条件.
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testCollectFeed()
    {
        $this->user->roles()->sync([2]);

        $this->user->wallet()->firstOrCreate([])->increment('balance', 1000000);

        $token = $this->guard()->login($this->user);

        $this->addTestFeedData($token);

        $data = ['amount' => 100, 'day' => 1];

        // 动态置顶: POST /feeds/:feed/pinneds
        $feedPinned = $this->post(
            $this->api."/{$this->feed['id']}/pinneds?token=".$token,
            $data
        );
        $feedPinned->assertStatus(201)->assertJsonStructure(['message']);

        // 动态评论置顶 POST /feeds/:feed/comments/:comment/pinneds
        $commentPinned = $this->post(
            $this->api."/{$this->feed['id']}/comments/{$this->comment['comment']['id']}/pinneds?token=".$token,
            $data);
        $commentPinned->assertStatus(201)->assertJsonStructure(['message']);

        // 动态评论置顶审核列表: GET /user/feed-comment-pinneds
        $this->get('/api/v2/user/feed-comment-pinneds?token='.$token)
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'channel',
                    'raw',
                    'target',
                    'user_id',
                    'amount',
                    'day',
                    'expires_at',
                    'created_at',
                    'updated_at',
                    'target_user',
                    'raw',
                    'feed',
                    'comment',
                ],
            ]);

        // 评论置顶审核通过: PATCH /feeds/:feed/comments/:comment/pinneds/:pinned
//        $res = $this->get($this->api . "{$this->feed['id']}/comments/{$this->comment['comment']['id']}/pinneds/:pinned?token=" . $token);
//        $res->assertStatus(200)->assertJsonStructure([]);
    }

    /**
     * @return Guard
     */
    protected function guard(): Guard
    {
        return Auth::guard('api');
    }

    /**
     * @param $token
     * @return mixed
     */
    protected function addTestFeedData($token)
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

        $this->comment = $this->post(
            $this->api."/{$this->feed['id']}/comments?token=".$token,
            ['body' => '测试评论']
        )->json();
    }
}
