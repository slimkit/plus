<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\FilterWordCategory;
use Zhiyi\Plus\Http\Controllers\Controller;

class FilterWordCategoryController extends Controller
{
    /**
     * 分类列表.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $items = FilterWordCategory::get();

        return response()->json($items, 200);
    }

    /**
     * 获取分类.
     *
     * @param FilterWordCategory $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(FilterWordCategory $category)
    {
        return response()->json($category, 200);
    }

    /**
     * 创建分类.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rule = ['name' => 'required|unique:filter_word_categories,name'];
        $msg = ['name.required' => '分类名必填', 'name.unique' => '分类名已存在'];

        $this->validate($request, $rule, $msg);

        FilterWordCategory::create($request->only('name'));

        return response()->json(['message' => ['添加分类成功']], 201);
    }

    /**
     * 更新分类.
     *
     * @param Request $request
     * @param FilterWordCategory $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, FilterWordCategory $category)
    {
        $rule = ['name' => 'required|unique:filter_word_categories,name,'.$category->id];
        $msg = ['name.required' => '分类名必填', 'name.unique' => '分类名已存在'];

        $this->validate($request, $rule, $msg);

        $category->update($request->only('name'));

        return response()->json(['message' => ['编辑分类成功']], 201);
    }

    /**
     * 删除分类.
     *
     * @param FilterWordCategory $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(FilterWordCategory $category)
    {
        if ($category->sensitives()->count()) {
            return response(['message' => ['该分类下还有敏感词存在，请先删除敏感词再删除分类']], 422);
        }

        $category->delete();

        return response()->json('', 204);
    }
}
