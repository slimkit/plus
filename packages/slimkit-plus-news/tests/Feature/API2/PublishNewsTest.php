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
use Zhiyi\Plus\Models\Tag as TagModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\TagCategory as TagCateModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate as NewsCateModel;

class PublishNewsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 发布一个资讯.
     *
     * @return mixed
     */
    public function testPublishNews()
    {
        $user = factory(UserModel::class)->create();
        $cate = factory(NewsCateModel::class)->create();

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', "/api/v2/news/categories/{$cate->id}/news", [
                'title' => 'test',
                'subject' => 'test',
                'content' => 'test',
                'tags' => $this->createTags(),
                'from' => 'test',
                'image' => null,
                'author' => 'test',
                'text_content' => 'test',
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 创建所需标签.
     *
     * @return mixed
     */
    protected function createTags()
    {
        $cate = factory(TagCateModel::class)->create();
        $tags = factory(TagModel::class, 3)->create([
            'tag_category_id' => $cate->id,
        ]);

        return $tags->pluck('id')->implode(',');
    }
}
