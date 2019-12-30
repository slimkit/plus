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

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zhiyi\Plus\Admin\Requests\UpdateImHelperUserRequest;
use Zhiyi\Plus\Support\Configuration;

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
