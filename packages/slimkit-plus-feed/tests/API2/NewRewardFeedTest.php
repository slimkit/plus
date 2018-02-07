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

class NewRewardFeedTest extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds';

    private $owner;

    private $other;

    public function setUp()
    {
        parent::setUp();

        $this->withMiddleware(['ability']);

        $this->owner = factory(User::class)->create();

        $this->owner->roles()->sync([2]);

        $this->other = factory(User::class)->create();

        $this->other
            ->newWallet()
            ->firstOrCreate([
            'balance' => 10000,
            'total_income' => 0,
            'total_expenses' => 0,
        ]);
    }

    public function testRewardFeed()
    {
        $this->addTestFeedData($this->owner);

        $response = $this->actingAs($this->other, 'api')
            ->post($this->api."/{$this->feed['id']}/new-rewards", ['amount' => 10]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * @param $token
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
            'amount' => 0,
            'images' => [],
        ];

        $this->feed = $this->actingAs($user, 'api')
            ->post('/api/v2/feeds', $data)
            ->json();
    }
}
