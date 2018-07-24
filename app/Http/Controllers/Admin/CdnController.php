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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Support\Configuration as ConfigurationRepository;

class CdnController extends Controller
{
    /**
     * Get selected cdn.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getCdnSelected()
    {
        return response()->json(['seleced' => config('cdn.default')], 200);
    }

    /**
     * Get qiniu setting.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function qiniu()
    {
        return response()->json([
            'domain' => config('cdn.generators.qiniu.domain'),
            'sign' => (bool) config('cdn.generators.qiniu.sign'),
            'expires' => (int) config('cdn.generators.qiniu.expires'),
            'ak' => config('cdn.generators.qiniu.ak'),
            'sk' => config('cdn.generators.qiniu.sk'),
            'type' => config('cdn.generators.qiniu.type'),
            'bucket' => config('cdn.generators.qiniu.bucket'),
        ], 200);
    }

    /**
     * Qiniu setting.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Support\Configuration $repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setQiniu(Request $request, ConfigurationRepository $repository)
    {
        $repository->set([
            'cdn.default' => 'qiniu',
            'cdn.generators.filesystem.disk' => 'public',
            'cdn.generators.qiniu.domain' => $request->input('domain'),
            'cdn.generators.qiniu.sign' => (bool) $request->input('sign'),
            'cdn.generators.qiniu.expires' => (int) $request->input('expires'),
            'cdn.generators.qiniu.ak' => $request->input('ak'),
            'cdn.generators.qiniu.sk' => $request->input('sk'),
            'cdn.generators.qiniu.type' => $request->input('type'),
            'cdn.generators.qiniu.bucket' => $request->input('bucket'),
        ]);

        return response()->json(['message' => '设置成功'], 201);
    }

    /**
     * Get local disk.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getFilesystemDisk()
    {
        return response()->json(['disk' => config('cdn.generators.filesystem.disk')], 200);
    }

    /**
     * 设置本地文件系统公开磁盘.
     *
     * @param \Zhiyi\Plus\Support\Configuration $repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setPublicDisk(ConfigurationRepository $repository)
    {
        $repository->set([
            'cdn.default' => 'filesystem',
            'cdn.generators.filesystem.disk' => 'public',
        ]);

        return response()->json(['message' => '设置成功！'], 201);
    }

    /**
     * 获取 local 磁盘公开地址.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getLocalDisk()
    {
        return response()->json(['public' => config('cdn.generators.filesystem.public')], 200);
    }

    /**
     * 设置 local 磁盘配置.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Support\Configuration $repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setLocalDisk(Request $request, ConfigurationRepository $repository)
    {
        $repository->set([
            'cdn.default' => 'filesystem',
            'cdn.generators.filesystem.disk' => 'local',
            'cdn.generators.filesystem.public' => $request->input('public'),
        ]);

        return response()->json(['message' => '设置成功！'], 201);
    }

    /**
     * 获取 S3 Disk 配置.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getS3Disk()
    {
        return response()->json(config('filesystems.disks.s3'), 200);
    }

    /**
     * 设置 S3 配置.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Support\Configuration $repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setS3Disk(Request $request, ConfigurationRepository $repository)
    {
        $repository->set([
            'cdn.default' => 'filesystem',
            'cdn.generators.filesystem.disk' => 's3',
            'filesystems.disks.s3.key' => $request->input('key'),
            'filesystems.disks.s3.secret' => $request->input('secret'),
            'filesystems.disks.s3.region' => $request->input('region'),
            'filesystems.disks.s3.bucket' => $request->input('bucket'),
        ]);

        return response()->json(['message' => '设置成功！'], 201);
    }

    /**
     * Get alioss setting.
     *
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function alioss()
    {
        return response()->json([
            'bucket' => config('cdn.generators.alioss.bucket'),
            'endpoint' => config('cdn.generators.alioss.endpoint'),
            'AccessKeyId' => config('cdn.generators.alioss.AccessKeyId'),
            'AccessKeySecret' => config('cdn.generators.alioss.AccessKeySecret'),
            'ssl' => (bool) config('cdn.generators.alioss.ssl'),
            'isPublic' => (bool) config('cdn.generators.alioss.public'),
            'expires' => (int) config('cdn.generators.alioss.expires'),
            'isCname' => (bool) config('cdn.generators.alioss.cname'),
        ], 200);
    }

    /**
     * alioss setting.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Support\Configuration $repository
     * @author BS <414606094@qq.com>
     */
    public function setAlioss(Request $request, ConfigurationRepository $repository)
    {
        $repository->set([
            'cdn.default' => 'alioss',
            'cdn.generators.filesystem.disk' => 'public',
            'cdn.generators.alioss.bucket' => $request->input('bucket'),
            'cdn.generators.alioss.endpoint' => $request->input('endpoint'),
            'cdn.generators.alioss.AccessKeyId' => $request->input('AccessKeyId'),
            'cdn.generators.alioss.AccessKeySecret' => $request->input('AccessKeySecret'),
            'cdn.generators.alioss.ssl' => (bool) $request->input('ssl'),
            'cdn.generators.alioss.public' => (bool) $request->input('isPublic'),
            'cdn.generators.alioss.expires' => (int) $request->input('expires'),
            'cdn.generators.alioss.cname' => (bool) $request->input('isCname'),
        ]);

        return response()->json(['message' => '设置成功'], 201);
    }
}
