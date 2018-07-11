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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Http\Requests\Admin\UpdateWebClientRequest;

class WebClientsController
{
    /**
     * Fetch web clients setting data.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function fetch(): JsonResponse
    {
        return response()->json([
            'web' => [
                'open' => (bool) config('http.web.open', false),
                'url' => (string) config('http.web.url', ''),
            ],
            'spa' => [
                'open' => (bool) config('http.spa.open', false),
                'url' => (string) config('http.spa.url', ''),
            ],
        ], 200);
    }

    /**
     * Update web clients settings.
     *
     * @param \Zhiyi\Plus\Http\Requests\Admin\UpdateWebClientRequest $request
     * @param \Zhiyi\Plus\Support\Configuration $config
     * @return \Illuminate\Http\Response
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(UpdateWebClientRequest $request, Configuration $config): Response
    {
        $config->set([
            'http.web.open' => (bool) $request->input('web.open'),
            'http.web.url' => $request->input('web.url'),
            'http.spa.open' => (bool) $request->input('spa.open'),
            'http.spa.url' => $request->input('spa.url'),
        ]);

        return response('', 204);
    }
}
