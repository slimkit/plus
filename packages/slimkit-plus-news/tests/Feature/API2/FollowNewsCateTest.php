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
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate as NewsCateModel;

class FollowNewsCateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 测试订阅资讯.
     *
     * @return mixed
     */
    public function testFollowNewsCate()
    {
        $user = factory(UserModel::class)->create();
        $cates = factory(NewsCateModel::class, 3)->create();

        $response = $this
            ->actingAs($user, 'api')
            ->json('patch', '/api/v2/news/categories/follows', ['follows' => $cates->pluck('id')->implode(',')]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }
}
