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
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;

class UserFollowController extends Controller
{
    /**
     * List followers of a user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followers(Request $request, ResponseFactoryContract $response, UserModel $user)
    {
        $target = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);

        $followers = $user->followers()
            ->with('extra')
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $user->getConnection()->transaction(function () use ($followers, $target, $response) {
            return $response->json($followers->map(function (UserModel $user) use ($target) {
                $user->following = $user->hasFollwing($target);
                $user->follower = $user->hasFollower($target);

                return $user;
            }))->setStatusCode(200);
        });
    }

    /**
     * List users followed by another user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followings(Request $request, ResponseFactoryContract $response, UserModel $user)
    {
        $target = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);

        $followings = $user->followings()
            ->with('extra')
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $user->getConnection()->transaction(function () use ($followings, $target, $response) {
            return $response->json($followings->map(function (UserModel $user) use ($target) {
                $user->following = $user->hasFollwing($target);
                $user->follower = $user->hasFollower($target);

                return $user;
            }))->setStatusCode(200);
        });
    }
}
