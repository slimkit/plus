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

use OSS\OssClient;
use Zhiyi\Plus\AppInterface;
use Illuminate\Support\Manager;
use function Zhiyi\Plus\setting;

class FilesystemManager extends Manager
{
    /**
     * Create the filesystem manager instance.
     * @param \Zhiyi\Plus\AppInterface $app
     */
    public function __construct(AppInterface $app)
    {
        parent::__construct($app);
    }

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
     */
    public function createLocalDriver(): Filesystems\FilesystemInterface
    {
        $localConfigure = setting('file-storage', 'filesystems.local', [
            'disk' => 'local',
        ]);
        $filesystem = $this
            ->app
            ->make(\Illuminate\Contracts\Filesystem\Factory::class)
            ->disk($localConfigure['disk']);

        return new Filesystems\LocalFilesystem($filesystem);
    }

    public function createAliyunOSSDriver(): Filesystems\FilesystemInterface
    {
        $aliyunOssConfigure = setting('file-storage', 'filesystems.aliyun-oss', []);
        $aliyunOssConfigure = array_merge([
            'bucket' => null,
            'access-key-id' => null,
            'access-key-secret' => null,
            'domain' => null,
            'timeout' => 3360,
        ], $aliyunOssConfigure);
        $oss = new OssClient(
            $aliyunOssConfigure['access-key-id'],
            $aliyunOssConfigure['access-key-secret'],
            $aliyunOssConfigure['domain'],
            true
        );

        return new Filesystems\AliyunOSS($oss, $aliyunOssConfigure);
    }
}
