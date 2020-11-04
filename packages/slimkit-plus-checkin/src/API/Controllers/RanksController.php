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

namespace SlimKit\PlusCheckIn\API\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\UserExtra as UserExtraModel;

class RanksController extends Controller
{
    /**
     * Get all users check-in ranks.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\UserExtra $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, UserExtraModel $model)
    {
        $user_id = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 10);
        $offset = max(0, $request->query('offset', 0));

        $users = $model->with([
            'user' => function ($query) {
                return $query->withTrashed();
            },
            'user.extra',
        ])
            ->orderBy('checkin_count', 'desc')
            ->orderBy('updated_at', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function (UserExtraModel $item) {
                return $item->user;
            })
            ->filter()
            ->map(function (UserModel $user, $key) {
                $user->follwing = false;
                $user->follower = false;

                return $user;
            })
            ->when($user_id, function (Collection $users) use ($user_id, $offset) {
                return $users->map(function (UserModel $user, $key) use ($user_id, $offset) {
                    $user->follwing = $user->hasFollwing($user_id);
                    $user->follower = $user->hasFollower($user_id);

                    $user->extra->rank = $offset + $key + 1;

                    return $user;
                });
            })
            ->values();

        return $response->json($users, 200);
    }
}
