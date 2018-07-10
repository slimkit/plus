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
use Zhiyi\Plus\Http\Requests\Admin\UpdateCorsRequest;

class CorsController
{
    /**
     * Fetch CORS settings.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function fetch(): JsonResponse
    {
        return response()->json([
            'credentials' => (bool) config('cors.allow-credentials'),
            'allowHeaders' => (array) config('cors.allow-headers'),
            'exposeHeaders' => (array) config('cors.expose-headers'),
            'origins' => (array) config('cors.origins'),
            'methods' => (array) config('cors.methods'),
            'maxAge' => (int) config('cors.max-age'),
        ], 200);
    }

    public function update(UpdateCorsRequest $request, Configuration $config): Response
    {
        $config->set([
            'cors.allow-credentials' => (bool) $request->input('credentials'),
            'cors.allow-headers' => $request->input('allowHeaders'),
            'cors.expose-headers' => $request->input('exposeHeaders'),
            'cors.origins' => $request->input('origins'),
            'cors.methods' => $request->input('methods'),
            'cors.max-age' => (int) $request->input('maxAge'),
        ]);

        return response('', 204);
    }
}
