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

namespace Zhiyi\Plus\API2\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class NewsPostController extends Controller
{
    /**
     * Create the news posts controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Destory a News post.
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News $post
     * @return mixed
     */
    public function destroy(News $post)
    {
        $this->authorize('delete', $post);

        // Database transaction
        DB::transaction(function () use ($post) {
            $post->pinned()->delete();
            $post->applylog()->delete();
            $post->reports()->delete();
            $post->tags()->detach();
            $post->delete();
        });

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
