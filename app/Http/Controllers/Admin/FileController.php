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

use Illuminate\Http\Request;
use Zhiyi\Plus\Support\Configuration;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Http\Controllers\Controller;

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
