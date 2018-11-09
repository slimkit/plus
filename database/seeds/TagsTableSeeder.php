<?php

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
