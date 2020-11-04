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
