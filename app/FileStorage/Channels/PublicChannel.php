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

use Zhiyi\Plus\AppInterface;
use Zhiyi\Plus\FileStorage\TaskInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;

class PublicChannel extends AbstractChannel
{
    protected $app;

    public function __construct(AppInterface $app)
    {
        $this->app = $app;
    }

    public function createTask(): TaskInterface
    {
        return $this->filesystem->createTask($this->request, $this->resource);
    }

    public function meta(): FileMetaInterface
    {
        return $this->filesystem->meta($this->resource);
    }

    public function url(?string $rule = null): string
    {
        return $this->filesystem->url($this->resource->getPath(), $rule);
    }

    public function callback(): void
    {
    }
}
