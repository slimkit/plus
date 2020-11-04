<?php

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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Tag;
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
