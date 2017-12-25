<?php

namespace Zhiyi\PlusGroup\Admin\Controllers;

use Validator;
use Illuminate\Http\Request;
use Zhiyi\PlusGroup\Models\Category;

class CategoryController
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $limit = (int) $request->query('limit',15);
        $offset = (int) $request->query('offset', 0);

        $items = Category::when($type, function ($query) {
            return $query->get();
        }, function ($query) use ($limit, $offset){
            return $query->orderBy('sort_by', 'desc')->limit($limit)->offset($offset)->get();
        });

        return response()->json($items, 200, ['x-total' => Category::count() ]);
    }

    public function delete(Request $request, Category $category)
    {
    	if ($category->groups()->count()) {
    		return response()->json(['message' => '该栏目下面存在圈子不能进行删除'], 403);
    	}
    	
    	$category->delete();

    	return response()->json(null, 204);
    }

    public function update(Request $request, Category $category)
    {
    	$value = $request->input('value');
        $type = $request->input('type');

        if (is_null($value)) {
            return response()->json(['message' => '修改项不能为空'], 422);
        }

		if ($type == 'name') {
    		$exists = Category::where($type, $value)->first();
    		if (! is_null($exists) && $exists->id !== $category->name) {
    			return response()->json(['message' => '分类已存在'], 422);
    		}
		}
    	$category->{$type} = $value;
    	$category->save();

    	return response()->json(null, 204);
    }

    private function validateStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:group_categories',
        ], [
        	'name.required' => '请填写栏目名称',
        	'name.unique' => '该分类名称已存在'
        ]);

        return $validator;
    }

    public function store(Request $request)
    {
    	$validator = $this->validateStore($request);

        if ($validator->fails()) {
        	return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $category = Category::create($request->all());

        return response()->json(['message' => '创建成功', 'category' => $category], 201);
    }
}
