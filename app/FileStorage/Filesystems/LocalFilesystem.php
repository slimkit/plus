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

namespace Zhiyi\Plus\FileStorage\Filesystems;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\FileStorage\Task;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\FileStorage\TaskInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;

class LocalFilesystem implements FilesystemInterface
{
    protected $filesystem;

    public function __construct(FilesystemContract $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function meta(ResourceInterface $resource): FileMetaInterface
    {
        return new Local\FileMeta($this->filesystem, $resource);
    }

    public function url(string $path, ?string $rule = null): string
    {
        //
    }

    public function delete(string $path): bool
    {
        //
    }

    public function createTask(Request $request, ResourceInterface $resource): TaskInterface
    {
        $expiresAt = (new Carbon)->addHours(
            setting('core', 'file:put-signature-expires-at', 1)
        );
        $uri = url()->temporarySignedRoute('storage:local-put', $expiresAt, [
            'channel' => $resource->getChannel(),
            'path' => base64_encode($resource->getPath()),
        ]);
        $user = $this->guard()->user();

        return new Task($resource, $uri, 'PUT', null, null, [
            'Authorization' => 'Bearer '.$this->guard()->login($user),
            'x-plus-storage-filename' => $request->input('filename'),
            'x-plus-storage-hash' => $request->input('hash'),
            'x-plus-storage-size' => $request->input('size'),
            'x-plus-storage-mime-type' => $request->input('mime_type'),
        ]);
    }

    public function put(string $path, $content): bool
    {
        return (bool) $this->filesystem->put($path, $content);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard(): Guard
    {
        return Auth::guard('api');
    }
}
