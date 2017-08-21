<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
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

		$tags = $tag_model
			->with('category')
			->withCount(['taggable'])
			->orderBy('id', 'asc')
			->paginate($limit);

		$tags->load('category');

		return response()->json($tags)->setStatusCode(200);
	}

	public function categories(Request $request, TagCategoryModel $tag_categories)
	{
		$limit = $request->input('per_page', 20);
		$page = $request->input('page', 1);

		$categories = $tag_categories
		->withCount('tags')
		->orderBy('id', 'desc')
		->paginate($limit);

		return response()->json($categories)->setStatusCode(200);
	}
}