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

namespace Zhiyi\Plus\FileStorage;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface StorageInterface
{
    /**
     * Create a file storage task.
     * @param \Illuminate\Http\Request $request
     * @return \Zhiyi\Plus\FileStorage\TaskInterface
     */
    public function createTask(Request $request): TaskInterface;

    /**
     * Get a file info.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return \Zhiyi\Plus\FileMetaInterface
     */
    public function meta(ResourceInterface $resource): FileMetaInterface;

    /**
     * Get a file response.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param string|null $rule
     * @return string
     */
    public function response(ResourceInterface $resource, ?string $rule = null): Response;

    /**
     * Deelte a resource.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return bool
     */
    public function delete(ResourceInterface $resource): ?bool;

    /**
     * Put a file.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param mixed $content
     * @return bool
     */
    public function put(ResourceInterface $resource, $content): bool;

    /**
     * A storage task callback handle.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return void
     */
    public function callback(ResourceInterface $resource): void;
}
