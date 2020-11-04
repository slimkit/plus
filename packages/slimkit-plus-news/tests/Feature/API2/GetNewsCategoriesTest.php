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

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

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
        $user = UserModel::factory()->create();

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
