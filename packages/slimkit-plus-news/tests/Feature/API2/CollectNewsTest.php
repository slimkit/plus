<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
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

class CollectNewsTest extends TestCase
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
     * 资讯收藏.
     *
     * @return mixed
     */
    public function testCollectNews()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/news/{$this->news->id}/collections");
        $response
            ->assertStatus(201);
    }

    /**
     * 获取收藏资讯。
     *
     * @return mixed
     */
    public function testGetCollectNews()
    {
        $this->news->collection($this->user->id);

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/news/collections');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);
    }

    /**
     * 取消收藏.
     *
     * @return mixed
     */
    public function testUnCollectNews()
    {
        $this->news->collection($this->user->id);

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('delete', "/api/v2/news/{$this->news->id}/collections");

        $response
            ->assertStatus(204);
    }
}
