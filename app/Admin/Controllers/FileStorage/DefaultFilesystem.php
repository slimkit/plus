<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Admin\Controllers\FileStorage;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function Zhiyi\Plus\setting;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class DefaultFilesystem
{
    /**
     * Get default filesystem.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {
        $defaultFilesystem = setting('file-storage', 'default-filesystem', 'local');

        return new JsonResponse(['filesystem' => $defaultFilesystem], Response::HTTP_OK);
    }

    /**
     * Update default filesystem.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): Response
    {
        $filesystem = $request->input('filesystem');
        if (! in_array($filesystem, ['local', 'AliyunOSS'])) {
            throw new UnprocessableEntityHttpException('设置的文件系统不合法');
        }

        $setting = setting('file-storage');
        $setting->set('default-filesystem', $filesystem);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
