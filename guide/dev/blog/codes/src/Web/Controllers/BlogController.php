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

namespace SlimKit\Plus\Packages\Blog\Web\Controllers;

use Illuminate\Routing\Controller;
use SlimKit\Plus\Packages\Blog\Models\Blog as BlogModel;

class BlogController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        // todo.
    }

    /**
     * Get the blog profile.
     *
     * @param SlimKit\Plus\Packages\Blog\Models\Blog $blog
     *
     * @return mixed
     */
    public function show(BlogModel $blog)
    {
        return view('plus-blog::blog-profile', [
            'blog'     => $blog,
            'articles' => $blog->articles()->paginate(15),
        ]);
    }
}
