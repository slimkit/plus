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

use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\Models\FeedTopic as TopicModel;
use Zhiyi\Plus\Packages\Feed\Admin\Requests\ListAllTopics as ListTopicsRequest;

class Topic extends Controller
{
    public function index(ListTopicsRequest $request, TopicModel $model)
    {
        $data = $model
            ->query()
            ->with([
                'creator',
            ])
            ->when($request->query('hot'), function ($query) {
                return $query->whereNotNull('hot_at');
            })
            ->when($name = $request->query('name'), function ($query) use ($name) {
                return $query->where('name', 'like', sprintf('%%%s%%', $name));
            })
            ->paginate($request->query('limit', 15), ['*'], 'page');

        return $data;
    }
}
