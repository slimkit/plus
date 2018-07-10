<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CertificationCategory;

class CertificationCategoryController extends Controller
{
    /**
     * certification categories list.
     * @param Request $request
     * @author: huhao <915664508@qq.com>
     */
    public function certifications()
    {
        $items = CertificationCategory::get();

        return response()->json($items)->setStatusCode(200);
    }

    /**
     * certification category detail.
     *
     * @param $name
     * @return $this
     * @author: huhao <915664508@qq.com>
     */
    public function show(CertificationCategory $category)
    {
        $category->icon = $category->icon;

        return response()->json($category)->setStatusCode(200);
    }

    /**
     * update certification category.
     * @param Request $request
     * @param $name
     * @return $this
     * @author: huhao <915664508@qq.com>
     */
    public function update(Request $request, CertificationCategory $category)
    {
        $rule = ['display_name' => 'required'];
        $msg = ['display_name.required' => '显示名称必须填写'];

        $this->validate($request, $rule, $msg);

        $category->display_name = $request->get('display_name');
        $category->description = $request->get('description');
        $response = $category->save();

        return response()->json([
            'message' => [
                $response === true ? '更新成功' : '更新失败',
            ],
        ])->setStatusCode($response === true ? 201 : 422);
    }

    public function iconUpload(Request $request, CertificationCategory $category)
    {
        $icon = $request->file('icon');

        if (! $icon->isValid()) {
            return response()->json(['messages' => [$icon->getErrorMessage()]], 422);
        }

        $category->storeAvatar($icon);

        $data['icon'] = $category->icon;

        return response()->json($data, 201);
    }
}
