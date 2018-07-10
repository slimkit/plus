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

class GetNewsListTest extends TestCase
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
        $this->news = factory(NewsModel::class, 10)->create([
            'title' => 'test',
            'user_id' => $this->user->id,
            'cate_id' => $this->cate->id,
            'audit_status' => 0,
        ]);
    }

    /**
     * 获取资讯列表.
     *
     * @return mixed
     */
    public function testGetNewsList()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/news');
        $response
            ->assertStatus(200);
    }

    /**
     * 测试搜索资讯.
     *
     * @return mixed
     */
    public function testSearchNews()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/news?key=test');
        $response
            ->assertStatus(200)
            ->assertJsonCount(10);
    }

    /**
     * 根据分类筛选资讯.
     *
     * @return mixed
     */
    public function testGetNewsByCate()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/news?cate_id='.$this->cate->id);
        $response
            ->assertStatus(200)
            ->assertJsonCount(10);
    }

    /**
     * 推荐筛选资讯.
     *
     * @return mixed
     */
    public function testGetNewsByRecommend()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/news?recommend=1');
        $response
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    /**
     * 获取单条资讯.
     *
     * @return mixed
     */
    public function testGetSingleNews()
    {
        $id = $this->news->pluck('id')->random();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', "/api/v2/news/{$id}/correlations");
        $response
            ->assertStatus(200);
    }
}
