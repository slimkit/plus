<?php

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

declare(srrict_types=1);

namespace Zhiyi\Plus\FileStorage;

use Illuminate\Http\Request;

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
     * Get a file URL.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param string|null $rule
     * @return string
     */
    public function url(ResourceInterface $resource, ?string $rule = null): string;

    /**
     * Deelte a resource.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return bool
     */
    public function delete(ResourceInterface $resource): ?bool;

    /**
     * Transform a resource channel.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param string $channel
     * @param \Illuminate\Http\Request $request
     * @return \Zhiyi\Plus\FileStorage\ResourceInterface
     */
    public function transform(ResourceInterface $resource, string $channel, Request $request): ResourceInterface;

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
