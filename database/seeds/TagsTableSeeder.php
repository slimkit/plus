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

use Zhiyi\Plus\Models\Tag;
use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\TagCategory;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = $this->createTagCategory();
        $tag = new Tag();
        $tag->name = '默认标签';
        $tag->tag_category_id = $category->id;
        $tag->save();
    }

    protected function createTagCategory()
    {
        $category = new TagCategory();
        $category->name = '默认分类';
        $category->save();

        return $category;
    }
}
