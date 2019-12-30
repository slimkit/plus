<?php

declare(strict_types=1);

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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\GoldType;

class GoldTypeController extends Controller
{
    public function types(Request $request)
    {
        $status = $request->get('status', null);

        $items = GoldType::when(! is_null($status), function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->orderBy('status', 'desc')
        ->get();

        return response()->json($items, 200);
    }

    public function storeType(Request $request)
    {
        $rule = ['name' => 'required', 'unit' => 'required'];
        $msg = ['name.required' => '名称必须填写', 'unit.required' => '单位必须填写'];

        $this->validate($request, $rule, $msg);

        $status = (int) $request->input('status');

        if ($status) {
            GoldType::where('id', '>', 0)->update(['status' => 0]);
        }

        if (GoldType::create($request->all())) {
            // 保存使用缓存
            GoldType::setCurrent($request->input('name'), $request->input('unit'));

            return response()->json(['message' => ['添加类别成功']], 201);
        } else {
            return response()->json(['message' => ['添加类别失败']], 500);
        }
    }

    public function openType(GoldType $type)
    {
        if (GoldType::count() > 1) {
            GoldType::where('id', '!=', $type->id)
                ->update(['status' => 0]);

            $type->status = ! $type->status;
            $type->save();
            // 保存使用缓存
            GoldType::setCurrent($type->name, $type->unit);

            return response()->json(['message' => [sprintf('启动"%s"分类成功', $type->name)]], 201);
        }
    }

    public function deleteType(GoldType $type)
    {
        if ($type->status !== 1) {
            $type->delete();

            return response()->json('', 204);
        }
    }
}
