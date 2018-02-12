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

namespace Zhiyi\PlusGroup\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;

class GetGroupCategoriesTest extends TestCase
{
    /**
     * 测试获取圈子分类接口.
     *
     * @return mixed
     */
    public function testGetGroupCategories()
    {
        $user = factory(UserModel::class)->create();

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/v2/plus-group/categories');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);
    }
}