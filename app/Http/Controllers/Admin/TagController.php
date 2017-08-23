<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Tag as TagModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Requests\API2\StoreTag;
use Zhiyi\Plus\Http\Requests\API2\UpdateTag;
use Zhiyi\Plus\Models\TagCategory as TagCategoryModel;

/**
 * 标签管理控制器.
 */
class TagController extends Controller
{
    /**
     * 标签列表.
     */
    public function lists(Request $request, TagModel $tag_model)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('per_page', 20);
        $cate = $request->input('cate', 0);

        $tags = $tag_model
            ->when($cate, function ($query) use ($cate) {
                return $query->where('tag_category_id', '=', $cate);
            })
            ->orderBy('weight', 'desc')
            ->with('category')
            ->withCount(['taggable'])
            ->orderBy('id', 'asc')
            ->paginate($limit);

        $tags->load('category');

        return response()->json($tags)->setStatusCode(200);
    }

    // 获取有分页的tag分类
    public function categories(Request $request)
    {
        $limit = $request->input('per_page', 20);
        $page = $request->input('page', 1);

        $categories = TagCategoryModel::withCount('tags')
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($limit);

        return response()->json($categories)->setStatusCode(200);
    }

    // 获取单个tag信息
    public function tag(Request $request, TagModel $tag)
    {
        return response()->json($tag)->setStatusCode(200);
    }

    /**
     * 删除tag.
     */
    public function delete(TagModel $tag)
    {
        if (! $tag->taggable()->count()) {
            $tag->delete();

            return response()->json()->setStatusCode(204);
        }

        return response()->json(['message' => '有资源使用该标签，不能删除，请先清理使用该标签的资源'])->setStatusCode(422);
    }

    // 获取无分页tag分类
    public function cateForTag()
    {
        $categories = TagCategoryModel::orderBy('id', 'asc')
            ->orderBy('weight', 'desc')
            ->get();

        return response()->json($categories)->setStatusCode(200);
    }

    // 新增tag
    public function store(StoreTag $request, TagModel $tag)
    {
        $name = $request->input('name');
        $tag_category_id = $request->input('category');
        $weight = $request->input('weight', 0);

        $tag->name = $name;
        $tag->tag_category_id = $tag_category_id;
        $tag->weight = $weight;

        $tag->save();

        return response()->json(['message' => '增加成功'])->setStatusCode(201);
    }

    /**
     * 更新tag.
     */
    public function update(UpdateTag $request, TagModel $tag)
    {
        $name = $request->input('name', '');
        $tag_category_id = $request->input('category');
        $weight = $request->input('weight');

        $name && $tag->name = $name;

        if ($tag_category_id) {
            $tag->tag_category_id = $tag_category_id;
        }

        $weight !== null && $tag->weight = $weight;

        if ($tag->save()) {
            return response()->json(['message' => '修改成功'])->setStatusCode(201);
        }

        return response()->json(['message' => '未知错误'])->setStatusCode(500);
    }

    /**
     * 删除标签分类.
     */
    public function deleteCategory(TagCategoryModel $cate)
    {
        if (! $cate->tags()->count()) {
            $cate->delete();

            return response()->json()->setStatusCode(204);
        }

        return response()->json(['message' => '该分类下还有标签存在，请先删除标签再删除分类'])->setStatusCode(422);
    }

    /**
     * 存储标签分类.
     */
    public function storeCate(Request $request, TagCategoryModel $tagcate)
    {
        $name = $request->input('name', '');
        $weight = $request->input('weight', 0);

        if (! $name) {
            return response()->json(['message' => '请输入分类名称'])->setStatusCode(400);
        }

        if ($tagcate->where('name', $name)->count()) {
            return response()->json(['message' => '分类已经存在'])->setStatusCode(422);
        }

        $tagcate->weight = $weight;
        $tagcate->name = $name;
        $tagcate->save();

        return response()->json(['message' => '创建分类成功', 'id' => $tagcate->id])->setStatusCode(201);
    }

    /**
     * 更新标签分类.
     */
    public function updateCate(Request $request, TagCategoryModel $cate)
    {
        $name = $request->input('name', '');
        $weight = $request->input('weight');
        if ($name && TagCategoryModel::where('name', $name)->count()) {
            return response()->json(['message' => '分类已经存在'])->setStatusCode(422);
        }

        $weight !== null && $cate->weight = $weight;
        $name && $cate->name = $name;
        $cate->save();

        return response()->json(['message' => '修改成功'])->setStatusCode(201);
    }
}
