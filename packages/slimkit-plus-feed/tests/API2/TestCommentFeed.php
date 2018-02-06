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
use Zhiyi\Plus\Models\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class TestPinnedFeed extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds';

    /**
     * 接口测试前置条件.
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

        $this->feed = $this->post($this->api . '?token=' . $this->token, $data)->json();
    }

    public function testCommentFeed()
    {
        // 评论动态: POST /feeds/:feed/comments
        $api = $this->api . "/{$this->feed['id']}/comments?token=" . $this->token;
        $res = $this->post($api, ['body' => '测试评论']);
        $this->response = $res->json();
        $res->assertStatus(201)->assertJsonStructure(['message', 'comment']);

        // 获取动态下的评论: GET /feeds/:feed/comments
        $api = $this->api . "/{$this->feed['id']}/comments?token=" . $this->token;
        $res = $this->get($api);
        $res->assertStatus(200)->assertJsonStructure(['pinneds', 'comments']);

        // 查看动态评论详情: GET /feeds/:feed/comments/:comment
        $api = $this->api . "/{$this->feed['id']}/comments/{$this->response['comment']['id']}?token=" . $this->token;
        $res = $this->get($api);
        $res->assertStatus(200)->assertJsonStructure([
            'id', 'user_id', 'target_user', 'reply_user', 'body', 'commentable_id', 'commentable_type', 'created_at', 'updated_at']);

        // 删除评论: DELETE /feeds/:feed/comments/:comment
        $api = $this->api . "/{$this->feed['id']}/comments/{$this->response['comment']['id']}?token=" . $this->token;
        $res = $this->delete($api);
        $res->assertStatus(204);
    }
}