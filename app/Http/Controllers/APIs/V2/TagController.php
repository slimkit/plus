<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Zhiyi\Plus\Models\TagCategory as TagCategoryModel;

class TagController extends Controller
{
    /**
     * Get all tags.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\TagCategory $categoryModel
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(ResponseFactoryContract $response, TagCategoryModel $categoryModel)
    {
        return $response->json(
            $categoryModel->with('tags')->get()
        )->setStatusCode(200);
    }
}
