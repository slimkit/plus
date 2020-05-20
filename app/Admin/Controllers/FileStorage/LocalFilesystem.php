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

namespace Zhiyi\Plus\Admin\Controllers\FileStorage;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use function Zhiyi\Plus\setting;

class LocalFilesystem
{
    /**
     * Get local filesystem.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {
        $configure = setting('file-storage', 'filesystems.local', []);
        $result = [
            'disk' => $configure['disk'] ?? 'local',
            'timeout' => $configure['timeout'] ?? 3360,
            'disks' => array_keys(config('filesystems.disks')),
        ];

        return new JsonResponse($result, Response::HTTP_OK);
    }

    /**
     * Update local filesystem.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): Response
    {
        $disk = $request->input('disk', 'local');
        $timeout = (int) $request->input('timeout');

        if (! in_array($disk, array_keys(config('filesystems.disks')))) {
            throw new UnprocessableEntityHttpException('选择的磁盘不存在！');
        }

        $setting = setting('file-storage');
        $setting->set('filesystems.local', [
            'disk' => $disk,
            'timeout' => $timeout,
        ]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
