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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Support\Configuration;

class FileController extends Controller
{
    /**
     * 获取附件基本配置.
     *
     * @param Repository $config
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function getConfig(Repository $config)
    {
        $max = $config->get('files.upload_max_size', 10240);

        return response()->json([
            'max_size' => $max,
        ])
        ->setStatusCode(200);
    }

    /**
     * 保存附件基本配置.
     *
     * @param Request $request
     * @param Configuration $config
     * @author BS <414606094@qq.com>
     */
    public function setConfig(Request $request, Configuration $config)
    {
        $config->set('files.upload_max_size', $request->input('max_size'));

        return response()->json(['message' => ['保存成功']], 201);
    }
}
