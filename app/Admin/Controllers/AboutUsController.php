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

class AboutUsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        return response()->json(['aboutUs' => config('site.aboutUs')], 200);
    }

    /**
     * @param Request       $request
     * @param Configuration $config
     * @return Response
     */
    public function store(Request $request, Configuration $config): Response
    {
//        dd($request->input('url'));
        $config->set('site.aboutUs.url', $request->input('url'));
        $config->set('site.aboutUs.content', $request->input('content'));

        return response('', 204);
    }
}
