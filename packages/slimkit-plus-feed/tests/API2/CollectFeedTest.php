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

class CollectFeedTest extends TestCase
{
    use DatabaseTransactions;

    protected $api = '/api/v2/feeds';

    protected $user;

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
     * 获取收藏列表.
     *
     * @return void
     */
    public function testCollectFeedList()
    {
        $response = $this->actingAs($this->user, 'api')->get($this->api.'/collections');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);
    }

    /**
     * 动态收藏.
     *
     * @return void
     */
    public function testCollectFeed()
    {
        $response = $this->actingAs($this->user, 'api')
                         ->post($this->api."/{$this->feed['id']}/collections");

        $response->assertStatus(201)
                 ->assertJsonStructure(['message']);
    }

    /**
     * 取消动态收藏.
     *
     * @return void
     */
    public function testUnCollectFeed()
    {
        $response = $this->actingAs($this->user, 'api')
                         ->delete($this->api."/{$this->feed['id']}/uncollect");

        $response->assertStatus(204);
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

        $this->feed = $this->actingAs($user, 'api')->post($this->api, $data)->json();
    }
}
