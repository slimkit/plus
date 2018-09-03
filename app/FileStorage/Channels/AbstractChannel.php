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
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;
use Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface;

abstract class AbstractChannel implements ChannelInterface
{
    protected $resource;
    protected $request;
    protected $filesystem;

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function setResource(ResourceInterface $resource): void
    {
        $this->resource = $resource;
    }

    public function setFilesystem(FilesystemInterface $filesystem): void
    {
        $this->filesystem = $filesystem;
    }

    public function put($context): bool
    {
        return $this->filesystem->put($this->resource->getPath(), $context);
    }

    public function response(?string $rule = null): Response
    {
        return $this->filesystem->response($this->resource, $rule);
    }
}
