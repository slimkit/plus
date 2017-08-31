<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

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
}
