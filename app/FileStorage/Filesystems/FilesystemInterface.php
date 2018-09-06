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
use Zhiyi\Plus\FileStorage\TaskInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;

interface FilesystemInterface
{
    /**
     * Get file meta.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return \Zhiyi\Plus\FileStorage\FileMetaInterface
     */
    public function meta(ResourceInterface $resource): FileMetaInterface;

    /**
     * Get file response.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param string|null $rule
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(ResourceInterface $resource, ?string $rule = null): Response;

    /**
     * Delete file.
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool;

    /**
     * Create upload task.
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return \Zhiyi\Plus\FileStorage\TaskInterface
     */
    public function createTask(Request $request, ResourceInterface $resource): TaskInterface;

    /**
     * Put a file.
     * @param string $path
     * @param mixed $contents
     * @return bool
     */
    public function put(string $path, $contents): bool;
}
