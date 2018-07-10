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

namespace Zhiyi\Plus\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Admin\Requests\UpdateImHelperUserRequest;

class ImHelperUserController extends Controller
{
    /**
     * Fetch im helper user id.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function fetch(): JsonResponse
    {
        return response()->json(['user' => config('im.helper-user')], 200);
    }

    /**
     * Update im helper user id.
     *
     * @param \Zhiyi\Plus\Admin\Requests\UpdateImHelperUserRequest $request
     * @param \Zhiyi\Plus\Support\Configuration $config
     * @return \Illuminate\Http\Response
     * @author Seven Du <shiweidu@outlook.com>
     */
    // public function update(UpdateImHelperUserRequest $request, Configuration $config): Response
    public function update(Request $request, Configuration $config): Response
    {
        $config->set('im.helper-user', $request->input('user', ''));

        return response('', 204);
    }
}
