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
            $categoryModel->with('tags')->orderBy('weight', 'desc')->get()
        )->setStatusCode(200);
    }
}
