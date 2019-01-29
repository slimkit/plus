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

namespace Zhiyi\Plus\API2\Controllers\User;

use Illuminate\Http\Request;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\API2\Resources\Ability as AbilityResource;

class AbilityController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * List all abilities.
     */
    public function __invoke(Request $request)
    {
        return AbilityResource::collection(
            $request->user()->ability()->all()->values()
        );
    }
}
