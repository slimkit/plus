<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Admin\Controllers\FileStorage;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function Zhiyi\Plus\setting;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSize
{
    /**
     * Get file storage validate size.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {
        $sysAllowUploadSize = '';
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
