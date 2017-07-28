<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\TagCategory as TagCategoryModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TagController extends Controller
{
    public function index(ResponseFactoryContract $response, TagCategoryModel $categoryModel)
    {
        return $response->json(
            $categoryModel->with('tags')->get()
        )->setStatusCode(200);
    }
}
