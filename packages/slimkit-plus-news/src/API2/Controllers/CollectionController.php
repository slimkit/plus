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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class CollectionController extends Controller
{
    /**
     * 收藏资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  News    $news
     * @return json
     */
    public function collection(Request $request, News $news)
    {
        $user = $request->user()->id;
        if ($news->collected($user)) {
            return response()->json([
                'message' => ['已收藏该资讯'],
            ])->setStatusCode(422);
        }

        $news->collection($user);

        return response()->json([
            'message' => ['收藏成功'],
        ])->setStatusCode(201);
    }

    /**
     * 取消收藏资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  News    $news
     * @return void
     */
    public function cancel(Request $request, News $news)
    {
        $user = $request->user()->id;
        if (! $news->collected($user)) {
            return response()->json([
                'message' => ['未收藏该资讯'],
            ])->setStatusCode(422);
        }
        $news->unCollection($user);

        return response()->json()->setStatusCode(204);
    }

    /**
     * 获取用户收藏资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $user->load(['newsCollections' => function ($query) use ($after, $limit) {
            return $query->when($after, function ($query) use ($after) {
                return $query->where('news.id', '<', $after);
            })->take($limit)
                ->select(['news.id', 'news.title', 'news.subject', 'news.created_at', 'news.updated_at', 'news.storage', 'news.cate_id', 'news.from', 'news.author', 'news.user_id', 'news.hits'])
                ->orderBy('news.id', 'desc');
        }]);

        $user->newsCollections->map(function ($collection) use ($user) {
            $collection->has_collect = $collection->collected($user);
            $collection->has_like = $collection->liked($user);
            $collection->addHidden('pinned', 'pivot');

            return $collection;
        });

        return response()->json($user->newsCollections, 200);
    }
}
