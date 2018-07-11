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

class GetNewsCategoriesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 已登录用户获取资讯栏目.
     *
     * @return mixed
     */
    public function testLoggedGetNewsCategories()
    {
        $user = factory(UserModel::class)->create();

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/v2/news/cates');
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['my_cates', 'more_cates']);
    }

    /**
     * 已登录用户获取资讯栏目.
     *
     * @return mixed
     */
    public function testNotLoggedGetNewsCategories()
    {
        $response = $this
            ->json('GET', '/api/v2/news/cates');
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['my_cates', 'more_cates']);
    }
}
