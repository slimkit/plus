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

namespace Zhiyi\Plus\Admin\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Admin\Resources\TrashedUserResource;
use Zhiyi\Plus\Models\User as UserModel;

class UserTrashedController extends Controller
{
    /**
     * List trashed users.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);

        $users = UserModel::onlyTrashed()
            ->limit($limit)
            ->offset($offset)
            ->latest()
            ->get();

        return TrashedUserResource::collection($users)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Restore a trashed user.
     *
     * @param int $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function restore(int $user)
    {
        $user = UserModel::withTrashed()
            ->where('id', $user)
            ->first();

        if ($user && $user->trashed()) {
            $user->restore();
        }

        return response('', 204);
    }
}
