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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function Zhiyi\Plus\setting;

class FileSize
{
    /**
     * Get file storage validate size.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {
        // Get configure.
        $configure = (array) setting('file-storage', 'task-create-validate', []);
        $result = [
            'size' => [
                'min' => intval($configure['file-min-size'] ?? 0),
                'max' => intval($configure['file-max-size'] ?? 0),
            ],
            'system' => [
                'max' => $this->getMaxFilesize(),
            ],
        ];

        return new JsonResponse($result, Response::HTTP_OK);
    }

    /**
     * Update file storage validate file size.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): Response
    {
        // Get configure.
        $setting = setting('file-storage');
        $setting->set('task-create-validate', array_merge((array) $setting->get('task-create-validate', []), array_filter([
            'file-min-size' => (int) $request->input('size.min', 0),
            'file-max-size' => (int) $request->input('size.max', 0),
        ])));

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Returns the maximum size of an uploaded file as configured in php.ini.
     *
     * @return int The maximum size of an uploaded file in bytes
     */
    protected function getMaxFilesize(): int
    {
        return UploadedFile::getMaxFilesize();
    }
}
