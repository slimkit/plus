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

namespace Zhiyi\Plus\FileStorage\Traits;

use Exception;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use Zhiyi\Plus\FileStorage\Resource;
use Zhiyi\Plus\FileStorage\StorageInterface;

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
        try {
            return $this->getFileStorageInstance()->meta(new Resource($resource));
        } catch (Exception $e) {
            return null;
        }

        return $resource;
    }
}
