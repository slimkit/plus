<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\FilterWordType;
use Zhiyi\Plus\Http\Controllers\Controller;

class FilterWordTypeController extends Controller
{
    /**
     * 过滤次类别.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = FilterWordType::get();

        return response()->json($items, 200);
    }

    /**
     * 更新状态.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(int $id)
    {
        $filterWordType = FilterWordType::find($id);
        $filterWordType->status = $filterWordType->status === 1 ? 0 : 1;
        $filterWordType->save();

        return response()->json(['message' => ['更新状态成功']], 200);
    }

    public function show(FilterWordType $type)
    {
        return response()->json($type);
    }

    public function store(Request $request, FilterWordType $filterWordType)
    {
        if (! $request->input('name')) {
            return response()->json(['message' => '请输入类别名'], 422);
        }

        $item = $filterWordType->where('name', $request->input('name'))->first();

        if ($item) {
            return response()->json(['message' => '类别已存在'], 422);
        }

        $filterWordType->create($request->all());

        return response()->json(['message' => '添加成功'], 201);
    }

    public function update(Request $request, FilterWordType $type)
    {
        $this->validate($request, [
            'name' => 'required|unique:filter_word_types,name,'.$type->id,
        ], [
            'name.required' => '类别名不能为空',
            'name.unique' => '类别名已存在',
        ]);

        $type->update($request->all());

        return response()->json(['message' => '编辑成功'], 201);
    }
}
