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

namespace Zhiyi\Plus\FileStorage;

interface TaskInterface
{
    /**
     * Get the task URI.
     * @return string
     */
    public function getUri(): string;

    /**
     * Get the task method.
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get the task headers.
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Get the task form.
     * @return null|array
     */
    public function getForm(): ?array;

    /**
     * Get the task file key.
     * @return null|string
     */
    public function getFileKey(): ?string;

    /**
     * Get resource node string.
     * @return string
     */
    public function getNode(): string;

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array;
}
