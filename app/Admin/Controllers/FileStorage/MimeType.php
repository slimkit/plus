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
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;
use function Zhiyi\Plus\setting;

class MimeType extends MimeTypeExtensionGuesser
{
    /**
     * Caching MIME types.
     * @var array
     */
    protected $mimeTypes = [];

    /**
     * Get file storage validate MIME types.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {
        // Get configure.
        $configure = setting('file-storage', 'task-create-validate', [
            'file-mime-types' => [],
        ]);
        $mimeTypes = array_filter($configure['file-mime-types'] ?? []);
        $extensions = array_map(function (string $mimeType) {
            return $this->guess($mimeType);
        }, $mimeTypes);

        return new JsonResponse($extensions, Response::HTTP_OK);
    }

    /**
     * Update file storage validate MIME types.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): Response
    {
        $extensions = array_filter((array) $request->input('extensions', []));
        $mimeTypes = array_map(function (string $extension) {
            return $this->mimeTypeGuess($extension);
        }, $extensions);
        $mimeTypes = array_unique($mimeTypes);

        $setting = setting('file-storage');
        $setting->set('task-create-validate', array_merge((array) $setting->get('task-create-validate', []), array_filter([
            'file-mime-types' => $mimeTypes,
        ])));

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * get extension MIME type.
     * @param string $extension
     * @return string
     */
    protected function mimeTypeGuess(string $extension): ?string
    {
        $mimeTypes = array_flip($this->defaultExtensions);
        $mimeTypes = array_merge($mimeTypes, [
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
        ]);

        return $mimeTypes[$extension] ?? null;
    }
}
