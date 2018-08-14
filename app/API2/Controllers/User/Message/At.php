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

namespace Zhiyi\Plus\API2\Controllers\User\Message;

use Illuminate\Http\Response;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\Models\AtMessage as AtMessageModel;
use Zhiyi\Plus\API2\Requests\User\Message\ListAtMessageLine;
use Zhiyi\Plus\API2\Resources\User\Message\AtMessage as AtMessageResource;

class At extends Controller
{
    /**
     * Create the at message controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function __invoke(ListAtMessageLine $request, AtMessageModel $model)
    {
        $direction = $request->query('direction', 'desc');
        $collection = $model
            ->query()
            ->when($index = $request->query('index'), function ($query) use ($index, $direction) {
                return $query->where('id', $direction === 'asc' ? '>' : '<', $index);
            })
            ->where('user_id', $request->user()->id)
            ->limit($request->query('limit', 15))
            ->orderBy('id', $direction)
            ->get();

        // 暂不提供支持，动态付费内容难以处理！代码请不要删除！
        // $relationships = array_filter(explode(',', $request->query('load')));
        // if ($relationships) {
        //     $collection->load($relationships);
        // }

        return AtMessageResource::collection($collection)
            ->toResponse($request)
            ->setStatusCode(Response::HTTP_OK);
    }
}
