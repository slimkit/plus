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

namespace Zhiyi\Plus\FileStorage\Traits;

use Exception;
use Zhiyi\Plus\FileStorage\Resource;
use Zhiyi\Plus\FileStorage\StorageInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;

trait EloquentAttributeTrait
{
    /**
     * Get file storage instance.
     * @return \Zhiyi\Plus\FileStorage\StorageInterface
     */
    protected function getFileStorageInstance(): StorageInterface
    {
        return app(StorageInterface::class);
    }

    /**
     * Get resource meta.
     * @param string $resource
     * @return null|\Zhiyi\Plus\FileStorage\FileMeatInterface
     */
    protected function getFileStorageResourceMeta(string $resource): ?FileMetaInterface
    {
        // Is local mode, throw exceptions.
        if (app()->isLocal()) {
            return $this->getFileStorageInstance()->meta(new Resource($resource));
        }

        try {
            return $this->getFileStorageInstance()->meta(new Resource($resource));
        } catch (Exception $e) {
            return $resource;
        }

        return $resource;
    }
}
