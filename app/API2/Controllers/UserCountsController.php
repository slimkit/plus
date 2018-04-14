<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\API2\Controllers;

use Illuminate\Support\Carbon;
use Zhiyi\Plus\API2\Resources\UserCountsResource;
use Zhiyi\Plus\Models\UserCount as UserCountModel;

class UserCountsController extends Controller
{
    /**
     * Create the Controller instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * The route controller to callable handle.
     *
     * @return \Zhiyi\Plus\API2\Resources\UserCountsResource
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __invoke(): UserCountsResource
    {
        $user = $this->request()->user();
        $counts = UserCountModel::where('user_id', $user->id)->get();
        $now = new Carbon();
        $data = [];
        $counts->each(function (UserCountModel $count) use ($now, &$data) {
            $data[$count->type] = $count->total;

            $count->total = 0;
            $count->read_at = $now;
            $count->save();
        });

        return new UserCountsResource($data);
    }
}
