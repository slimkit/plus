<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate as NewsCateModel;

class RewardNewsTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $cate;

    protected $news;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(UserModel::class)->create();
        $this->cate = factory(NewsCateModel::class)->create();
        $this->news = factory(NewsModel::class)->create([
            'title' => 'test',
            'user_id' => $this->user->id,
            'cate_id' => $this->cate->id,
            'audit_status' => 1,
        ]);
    }

    /**
     * 旧版打赏接口.
     *
     * @return mixed
     */
    public function testRewardNews()
    {
        $this->user->wallet()->increment('balance', 100);
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/news/{$this->news->id}/rewards", [
                'amount' => 100,
            ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 新版打赏接口.
     *
     * @return mixed
     */
    public function testNewRewardNews()
    {
        $other = factory(UserModel::class)->create();
        $other->currency()->increment('sum', 100);

        $response = $this
            ->actingAs($other, 'api')
            ->json('POST', "/api/v2/news/{$this->news->id}/new-rewards", [
                'amount' => 100,
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 资讯打赏列表.
     *
     * @return mixed
     */
    public function testGetNewsRewards()
    {
        $response = $this
            ->json('GET', "/api/v2/news/{$this->news->id}/rewards");
        $response
            ->assertStatus(200);
    }

    /**
     * 资讯打赏统计.
     *
     * @return mixed
     */
    public function testNewsRewardCount()
    {
        $response = $this
            ->json('GET', "/api/v2/news/{$this->news->id}/rewards/sum");
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['count', 'amount']);
    }
}
