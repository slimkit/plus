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

class GetDetailFeedTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    /**
     * 前置条件.
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->user->roles()->sync([2]);
    }

    public function testGetFeedList()
    {
        $this->addTestFeedData($this->user);

        $response = $this->get('/api/v2/feeds/'.$this->feed['id']);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'feed_content',
                'feed_from',
                'like_count',
                'feed_view_count',
                'feed_comment_count',
                'feed_latitude',
                'feed_longtitude',
                'feed_geohash',
                'audit_status',
                'feed_mark',
                'created_at',
                'updated_at',
                'deleted_at',
                'has_collect',
                'has_like',
                'reward',
                'images',
                'paid_node',
                'likes',
            ]);
    }

    /**
     * @param $user
     * @return mixed
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

        $this->feed = $this->actingAs($user, 'api')->post('api/v2/feeds', $data)->json();
    }
}
