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

class Task implements TaskInterface
{
    protected $resource;
    protected $uri;
    protected $method;
    protected $from;
    protected $fileKey;
    protected $headers;

    public function __construct(ResourceInterface $resource, string $uri, string $method, ?array $form = null, ?string $fileKey = null, array $headers = [])
    {
        $this->resource = $resource;
        $this->uri = $uri;
        $this->method = $method;
        $this->form = $form;
        $this->fileKey = $fileKey;
        $this->headers = $headers;
    }

    /**
     * Get the task URI.
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get the task method.
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the task headers.
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get the task form.
     * @return null|array
     */
    public function getForm(): ?array
    {
        return $this->form;
    }

    /**
     * Get the task file key.
     * @return null|string
     */
    public function getFileKey(): ?string
    {
        return $this->fileKey;
    }

    /**
     * Get resource node string.
     * @return string
     */
    public function getNode(): string
    {
        return (string) $this->resource;
    }
}
