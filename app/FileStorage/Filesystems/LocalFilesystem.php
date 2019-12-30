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

namespace Zhiyi\Plus\FileStorage\Filesystems;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Zhiyi\Plus\FileStorage\Task;
use Zhiyi\Plus\FileStorage\TaskInterface;
use function Zhiyi\Plus\setting;

class LocalFilesystem implements FilesystemInterface
{
    /**
     * The local filesystem.
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Cache the file metas.
     * @var array<\Zhiyi\Plus\FileStorage\FileMetaInterface>
     */
    protected $metas = [];

    /**
     * Create the filesystem driver instance.
     * @param \\Illuminate\Contracts\Filesystem\Filesystem $folesystem
     */
    public function __construct(FilesystemContract $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Get file meta.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return \Zhiyi\Plus\FileStorage\FileMetaInterface
     */
    public function meta(ResourceInterface $resource): FileMetaInterface
    {
        $resourceString = (string) $resource;
        $meta = $this->metas[$resourceString] ?? null;

        if ($meta instanceof FileMetaInterface) {
            return $meta;
        }

        return $this->metas[$resourceString] = new Local\FileMeta($this->filesystem, $resource);
    }

    /**
     * Get file response.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param string|null $rule
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(ResourceInterface $resource, ?string $rule = null): Response
    {
        $realPath = $this->filesystem->path($resource->getPath());
        if ($this->meta($resource)->hasImage()) {
            $rule = new Local\RuleParser($rule);
            if (
                $rule->getQuality() >= 90 &&
                ! $rule->getBlur() &&
                ! $rule->getWidth() &&
                ! $rule->getHeight() &&
                strtolower($this->meta($resource)->getMimeType()) === 'image/gif'
            ) {
                return $this->filesystem->response($resource->getPath());
            }

            $pathinfo = \League\Flysystem\Util::pathinfo($resource->getPath());
            $cachePath = sprintf('%s/%s/%s.%s', $pathinfo['dirname'], $pathinfo['filename'], $rule->getFilename(), $pathinfo['extension']);
            if ($this->filesystem->has($cachePath)) {
                return $this->filesystem->response($cachePath);
            }

            $image = Image::make($realPath);
            $image->blur($rule->getBlur());
            if (($image->width() > $rule->getWidth() || $image->height() > $rule->getHeight()) && ($rule->getWidth() || $rule->getHeight())) {
                $image->resize($rule->getWidth() ?: null, $rule->getHeight() ?: null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            $contents = $image->encode($image->extension, $rule->getQuality());
            $this->filesystem->put($cachePath, $contents);

            return $image->response();
        }

        return new BinaryFileResponse($realPath);
    }

    /**
     * Delete file.
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        $pathinfo = \League\Flysystem\Util::pathinfo($path);
        $dir = sprintf('%s/%s', $pathinfo['dirname'], $pathinfo['filename']);

        $this->filesystem->deleteDir($dir);
        $this->filesystem->delete($path);

        return true;
    }

    /**
     * Create upload task.
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return \Zhiyi\Plus\FileStorage\TaskInterface
     */
    public function createTask(Request $request, ResourceInterface $resource): TaskInterface
    {
        $expiresAt = (new Carbon)->addSeconds(
            setting('file-storage', 'filesystems.local', [])['timeout'] ?? 3600
        );
        $uri = url()->temporarySignedRoute('storage:local-put', $expiresAt, [
            'channel' => $resource->getChannel(),
            'path' => base64_encode($resource->getPath()),
        ]);
        $user = $this->guard()->user();

        return new Task($resource, $uri, 'PUT', null, null, [
            'Authorization' => 'Bearer '.$this->guard()->login($user),
            'x-plus-storage-hash' => $request->input('hash'),
            'x-plus-storage-size' => $request->input('size'),
            'x-plus-storage-mime-type' => $request->input('mime_type'),
        ]);
    }

    /**
     * Put a file.
     * @param string $path
     * @param mixed $contents
     * @return bool
     */
    public function put(string $path, $contents): bool
    {
        return (bool) $this->filesystem->put($path, $contents);
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
