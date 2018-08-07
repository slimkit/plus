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

namespace Zhiyi\Plus\Packages\Feed\Admin\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\Models\FeedTopic as TopicModel;
use Zhiyi\Plus\API2\Controllers\Feed\Topic as Controller;
use Zhiyi\Plus\Packages\Feed\Admin\Requests\ListAllTopics as ListTopicsRequest;

class Topic extends Controller
{
    public function __construct()
    {
        // Add DisposeSensitive middleware.
        $this
            ->middleware('sensitive:name,desc')
            ->only(['create', 'update']);
    }

    public function adminListTopics(ListTopicsRequest $request, TopicModel $model): JsonResponse
    {
        $data = $model
            ->query()
            ->with([
                'creator',
            ])
            ->when($id = $request->query('id'), function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when($request->query('hot'), function ($query) {
                return $query->whereNotNull('hot_at');
            })
            ->when($name = $request->query('name'), function ($query) use ($name) {
                return $query->where('name', 'like', sprintf('%%%s%%', $name));
            })
            ->orderBy($request->query('orderBy', 'id'), $request->query('direction', 'desc'))
            ->paginate($request->query('limit', 15), ['*'], 'page');

        return new JsonResponse($data);
    }

    public function hotToggle(TopicModel $topic)
    {
        $topic->hot_at = $topic->hot_at ? null : new Carbon;
        $topic->save();

        return response('', 204);
    }

    public function destroy(TopicModel $topic)
    {
        return $topic->getConnection()->transaction(function () use ($topic) {
            // 删除关联用户
            $topic->users()->sync([], true);

            // 删除动态关联
            $topic->feeds()->sync([], true);

            // 删除话题
            $topic->delete();

            return response('', 204);
        });
    }
}
