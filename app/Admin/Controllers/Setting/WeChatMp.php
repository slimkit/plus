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
use Illuminate\Http\Response;
use Zhiyi\Plus\Admin\Controllers\Controller;
use Zhiyi\Plus\Admin\Requests\SetWeChatMpConfigure as SetWeChatMpConfigureRequest;
use function Zhiyi\Plus\setting;

class WeChatMp extends Controller
{
    /**
     * Get configure.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConfigure(): JsonResponse
    {
        $settings = setting('user', 'vendor:wechat-mp', [
            'appid' => '',
            'secret' => '',
        ]);

        return new JsonResponse($settings, Response::HTTP_OK);
    }

    /**
     * set configure.
     * @param \Zhiyi\Plus\Admin\Requests\SetWeChatMpConfigure $request
     * @return \Illuminate\Http\Response
     */
    public function setConfigure(SetWeChatMpConfigureRequest $request)
    {
        setting('user')->set('vendor:wechat-mp', [
            'appid' => $request->input('appid'),
            'secret' => $request->input('secret'),
        ]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
