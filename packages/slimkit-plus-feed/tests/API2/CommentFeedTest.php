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
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentFeedTest extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds';

    private $user;

    private $feed;

    /**
     * 接口测试前置条件.
     */
    public function setUp()
    {
        parent::setUp();

        $this->withMiddleware(['ability']);

        $this->user = factory(User::class)->create();

        $this->addTestFeedData($this->user);
    }

    /**
     * 动态评论.
     *
     * @return void
     */
    public function testCommentFeed()
    {
        $response = $this->commentFeed();

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'comment']);
    }

    /**
     * 获取动态评论.
     *
     * @return void
     */
    public function testGetFeedComments()
    {
        $response = $this->actingAs($this->user, 'api')
                         ->get($this->api."/{$this->feed['id']}/comments");

        $response->assertStatus(200)
                 ->assertJsonStructure(['pinneds', 'comments']);
    }

    /**
     * 获取动态评论.
     *
     * @return void
     */
    public function testGetFeedCommentDetail()
    {
        $comment = $this->commentFeed()->json();

        $response = $this->actingAs($this->user, 'api')
                         ->get($this->api."/{$this->feed['id']}/comments/{$comment['comment']['id']}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'target_user',
                'reply_user',
                'body',
                'commentable_id',
                'commentable_type',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * 删除评论评论.
     *
     * @return void
     */
    public function testDeleteFeedComment()
    {
        $comment = $this->commentFeed()->json();

        $response = $this->actingAs($this->user, 'api')
            ->delete($this->api."/{$this->feed['id']}/comments/{$comment['comment']['id']}");

        $response->assertStatus(204);
    }

    /**
     * 评论动态.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function commentFeed()
    {
        return $this->actingAs($this->user, 'api')
                    ->post($this->api."/{$this->feed['id']}/comments", ['body' => '测试评论']);
    }

    /**
     * @param $token
     * @return void
     */
    protected function addTestFeedData($user)
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

        $this->feed = $this->actingAs($user, 'api')
                           ->post($this->api, $data)
                           ->json();
    }
}
