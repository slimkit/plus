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

namespace Zhiyi\Plus\API2\Controllers\User\Message;

use Illuminate\Http\Response;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\API2\Requests\User\Message\ListAtMessageLine;
use Zhiyi\Plus\API2\Resources\User\Message\AtMessage as AtMessageResource;
use Zhiyi\Plus\Models\AtMessage as AtMessageModel;

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
