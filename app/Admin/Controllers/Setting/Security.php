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

namespace Zhiyi\Plus\Admin\Controllers\Setting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zhiyi\Plus\Admin\Controllers\Controller;
use function Zhiyi\Plus\setting;

class Security extends Controller
{
    /**
     * Get pay validate password switch.
     * @return \Illuminate\Http\JsonResponse
     */
    public function payValidateSwitch(): JsonResponse
    {
        $switch = (bool) setting('pay', 'validate-password', false);

        return new JsonResponse(['switch' => $switch], JsonResponse::HTTP_OK);
    }

    /**
     * Change pay validate password switch.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changePayValidateSwitch(Request $request): Response
    {
        $switch = (bool) $request->input('switch', false);
        setting('pay')->set('validate-password', $switch);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
