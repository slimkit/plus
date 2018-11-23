<?php

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

namespace Zhiyi\Plus\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\Tag as TagModel;
use Zhiyi\Plus\Models\TagCategory as TagCategoryModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TagsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The test set up.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setUp()
    {
        parent::setUp();

        app(TagCategoryModel::class)->insert(['id' => '1', 'name' => '测试分类']);
        app(TagModel::class)->insert(['name' => '标签1', 'tag_category_id' => 1]);
        app(TagModel::class)->insert(['name' => '标签2', 'tag_category_id' => 1]);
    }

    /**
     * 测试获取标签.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testGetTags()
    {
        $response = $this->getJson('/api/v2/tags');

        $response->assertStatus(200);

        collect($response->json())->map(function ($array) {
            $this->assertArrayHasKey('id', $array);
            $this->assertArrayHasKey('name', $array);
            $this->assertArrayHasKey('tags', $array);
        });
    }
}
