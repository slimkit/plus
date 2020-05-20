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

namespace Zhiyi\Plus\FileStorage\Channels;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;

abstract class AbstractChannel implements ChannelInterface
{
    /**
     * The resource.
     * @var \Zhiyi\Plus\FileStorage\ResourceInterface
     */
    protected $resource;

    /**
     * A request instance.
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Filesystem.
     * @var \Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface
     */
    protected $filesystem;

    /**
     * Set request.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Set resource.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return void
     */
    public function setResource(ResourceInterface $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * Set filesystem.
     * @param \Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface $filesystem
     * @return void
     */
    public function setFilesystem(FilesystemInterface $filesystem): void
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Put a file.
     * @param mixed $contents
     * @return vodi
     */
    public function put($contents): bool
    {
        return $this->filesystem->put($this->resource->getPath(), $contents);
    }

    /**
     * Get a resource response.
     * @param string|null $rule
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(?string $rule = null): Response
    {
        return $this->filesystem->response($this->resource, $rule);
    }
}
