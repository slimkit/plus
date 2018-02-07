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

class GetFeedTest extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds';

    private $user;

    private $structure = [
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
    ];

    /**
     * 前置条件.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->withMiddleware(['ability']);

        $this->user = factory(User::class)->create();

        $this->user->roles()->sync([2]);

        $this->addTestFeedData($this->user);
    }

    /**
     * 获取动态列表.
     *
     * @return void
     */
    public function testGetFeedList()
    {
        $response = $this->actingAs($this->user, 'api')->get($this->api);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['ad', 'pinned', 'feeds']);
    }

    /**
     * 获取动态详情.
     *
     * @return void
     */
    public function testGetFeedDetail()
    {
        $response = $this->actingAs($this->user, 'api')->get($this->api.'/'.$this->feed['id']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * 填充动态数据.
     *
     * @param $user
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
