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

use Illuminate\Support\Manager;
use OSS\OssClient;
use Zhiyi\Plus\AppInterface;
use function Zhiyi\Plus\setting;

class FilesystemManager extends Manager
{
    /**
     * Get the default driver name.
     */
    public function getDefaultDriver()
    {
        return setting('file-storage', 'default-filesystem', 'local');
    }

    /**
     * Create local driver.
     * @return \Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createLocalDriver(): Filesystems\FilesystemInterface
    {
        $localConfigure = setting('file-storage', 'filesystems.local', [
            'disk' => 'local',
        ]);
        $filesystem = $this
            ->container
            ->make(\Illuminate\Contracts\Filesystem\Factory::class)
            ->disk($localConfigure['disk']);

        return new Filesystems\LocalFilesystem($filesystem);
    }

    /**
     * Create Aliyun OSS filesystem driver.
     * @return \Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface
     * @throws \OSS\Core\OssException
     */
    public function createAliyunOSSDriver(): Filesystems\FilesystemInterface
    {
        $aliyunOssConfigure = setting('file-storage', 'filesystems.aliyun-oss', []);
        $aliyunOssConfigure = array_merge([
            'bucket' => null,
            'access-key-id' => null,
            'access-key-secret' => null,
            'domain' => null,
            'inside-domain' => null,
            'timeout' => 3600,
        ], $aliyunOssConfigure);
        $oss = new OssClient(
            $aliyunOssConfigure['access-key-id'] ?? null,
            $aliyunOssConfigure['access-key-secret'] ?? null,
            $aliyunOssConfigure['domain'] ?? null,
            true
        );
        $insideOss = new OssClient(
            $aliyunOssConfigure['access-key-id'] ?? null,
            $aliyunOssConfigure['access-key-secret'] ?? null,
            $aliyunOssConfigure['inside-domain'] ?? null,
            true
        );

        return new Filesystems\AliyunOssFilesystem($oss, $insideOss, $aliyunOssConfigure);
    }
}
