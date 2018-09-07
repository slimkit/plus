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

namespace Zhiyi\Plus\FileStorage\Channels;

use Illuminate\Http\Request;
use Zhiyi\Plus\FileStorage\TaskInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;
use Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface;

interface ChannelInterface
{
    /**
     * Set resource.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return void
     */
    public function setResource(ResourceInterface $resource): void;

    /**
     * Set request.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function setRequest(Request $request): void;

    /**
     * Set filesystem.
     * @param \Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface $filesystem
     * @return void
     */
    public function setFilesystem(FilesystemInterface $filesystem): void;

    /**
     * Create a upload task.
     * @return \Zhiyi\Plus\FileStorage\TaskInterface
     */
    public function createTask(): TaskInterface;

    /**
     * Get a resource meta.
     * @return \Zhiyi\Plus\FileStorage\FileMetaInterface
     */
    public function meta(): FileMetaInterface;

    /**
     * Get a resource response.
     * @param string|null $rule
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(?string $rule = null): Response;

    /**
     * Uploaded callback handler.
     * @return void
     */
    public function callback(): void;

    /**
     * Put a file.
     * @param mixed $contents
     * @return vodi
     */
    public function put($contents): bool;
}
