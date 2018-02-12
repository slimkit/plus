<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\PlusGroup\API\Controllers;

use Zhiyi\PlusGroup\Models\Category as CategoryModel;

class CategoriesController
{
    /**
     * List all group categores.
     *
     * @return mied
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(CategoryModel $CategoryModel)
    {
        $categories = $CategoryModel->where('status', 0)->orderBy('sort_by', 'asc')->get();

        return response()->json($categories, 200);
    }
}
