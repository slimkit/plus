<?php

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
