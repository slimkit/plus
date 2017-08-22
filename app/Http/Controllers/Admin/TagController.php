<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Requests\API2\StoreTag;
use Zhiyi\Plus\Models\Tag as TagModel;
use Zhiyi\Plus\Models\TagCategory as TagCategoryModel;
use Zhiyi\Plus\Http\Controllers\Controller;

/**
 * 标签管理控制器
 */
class TagController extends Controller
{
	/**
	 * 标签列表
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
		->orderBy('id', 'desc')
		->paginate($limit);

		return response()->json($categories)->setStatusCode(200);
	}

	// 获取单个tag信息
	public function tag(Request $request, TagModel $tag)
	{
		return response()->json($tag)->setStatusCode(200);
	}

	// 获取无分页tag分类
	public function cateForTag() {
		$categories = TagCategoryModel::orderBy('id', 'asc')
			->get();

		return response()->json($categories)->setStatusCode(200);
	}

	// 新增tag
	public function store(StoreTag $request, TagModel $tag)
	{
		$name = $request->input('name');
		$tag_category_id = $request->input('category');

		$tag->name = $name;
		$tag->tag_category_id = $tag_category_id;

		$tag->save();

		return response()->json(['message' => '增加成功'])->setStatusCode(201);
	}

	/**
	 * 更新tag
	 */
	public function update (Request $request, TagModel $tag)
	{
		$name = $request->input('name', '');
		$tag_category_id = $request->input('category', 0);

		if(!$name && !$tag_category_id) abort(400, '参数传递错误');

		if($name) $tag->name = $name;
		if($tag_category_id) $tag->tag_category_id = $tag_category_id;

		dd($tag->save());
	}
}