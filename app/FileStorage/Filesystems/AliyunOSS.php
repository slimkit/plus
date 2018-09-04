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

namespace Zhiyi\Plus\FileStorage\Filesystems;

use OSS\OssClient;
use Illuminate\Http\Request;
use Zhiyi\Plus\FileStorage\Task;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Zhiyi\Plus\FileStorage\TaskInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;

class AliyunOSS implements FilesystemInterface
{
    protected $oss;
    protected $bucket;
    protected $metas = [];

    public function __construct(OssClient $oss, string $bucket)
    {
        $this->bucket = $bucket;
        $this->oss = $oss;
    }

    /**
     * Create upload task.
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return \Zhiyi\Plus\FileStorage\TaskInterface
     */
    public function createTask(Request $request, ResourceInterface $resource): TaskInterface
    {
        $user = $this->guard()->user();
        $expiresSecond = 360;
        $headers = [
            OssClient::OSS_CONTENT_DISPOSTION => 'attachment;filename='.$request->input('filename'),
            OssClient::OSS_CONTENT_MD5 => base64_encode(pack('H*', $request->input('hash'))),
            OssClient::OSS_CONTENT_LENGTH => $request->input('size'),
            OssClient::OSS_CONTENT_TYPE => $request->input('mime_type'),
            OssClient::OSS_CALLBACK => json_encode([
                'callbackBodyType' => 'application/json',
                'callbackUrl' => 'http://test-plus.zhibocloud.cn/oss-callback.php',
                // 'callbackUrl' => route('storage:callback', [
                //     'channel' => $resource->getChannel(),
                //     'path' => base64_encode($resource->getPath()),
                // ]),
                'callbackBody' => json_encode([
                    'jwt' => '${x:auth-token}',
                ]),
            ]),
            OssClient::OSS_CALLBACK_VAR => json_encode([
                'x:auth-token' => $this->guard()->login($user),
            ]),
        ];

        $url = $this->oss->signUrl(
            $this->bucket,
            $resource->getPath(),
            $expiresSecond,
            OssClient::OSS_HTTP_PUT,
            $headers
        );
        $headers[OssClient::OSS_CALLBACK] = base64_encode($headers[OssClient::OSS_CALLBACK]);
        $headers[OssClient::OSS_CALLBACK_VAR] = base64_encode($headers[OssClient::OSS_CALLBACK_VAR]);

        return new Task($resource, $url, 'PUT', null, null, $headers);
    }

    /**
     * Get file meta.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return \Zhiyi\Plus\FileStorage\FileMetaInterface
     */
    public function meta(ResourceInterface $resource): FileMetaInterface
    {
        $resourceString = (string) $resource;
        $meta = $this->metas[$resourceString] ?? null;
        if ($meta instanceof FileMetaInterface) {
            return $meta;
        }

        return $this->metas[$resourceString] = new AliyunOSS\FileMeta($this->oss, $resource, $this->bucket);
    }

    /**
     * Get file response.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param string|null $rule
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(ResourceInterface $resource, ?string $rule = null): Response
    {
        $url = $this->oss->signUrl($this->bucket, $resource->getPath(), 3600, 'GET', [
            OssClient::OSS_PROCESS => $rule,
        ]);

        return new RedirectResponse($url, Response::HTTP_TEMPORARY_REDIRECT);
    }

    /**
     * Delete file.
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        return false;
    }

    /**
     * Put a file.
     * @param string $path
     * @param mixed $contents
     * @return bool
     */
    public function put(string $path, $contents): bool
    {
        return false;
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard(): Guard
    {
        return Auth::guard('api');
    }
}
