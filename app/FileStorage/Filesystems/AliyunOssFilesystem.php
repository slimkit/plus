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

namespace Zhiyi\Plus\FileStorage\Filesystems;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OSS\OssClient;
use Symfony\Component\HttpFoundation\Response;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Zhiyi\Plus\FileStorage\Task;
use Zhiyi\Plus\FileStorage\TaskInterface;

class AliyunOssFilesystem implements FilesystemInterface
{
    protected $oss;
    protected $insideOss;
    protected $configure;
    protected $metas = [];

    /**
     * Create the Aliyun OSS filesystem instance.
     * @param \OSS\OssClient $oss
     * @param array $configure
     */
    public function __construct(OssClient $oss, OssClient $insideOss, array $configure)
    {
        $this->configure = $configure;
        $this->oss = $oss;
        $this->insideOss = $insideOss;
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
        $headers = [
            // OssClient::OSS_CONTENT_DISPOSTION => 'attachment;filename='.$request->input('filename'),
            OssClient::OSS_CONTENT_MD5 => base64_encode(pack('H*', $request->input('hash'))),
            OssClient::OSS_CONTENT_LENGTH => $request->input('size'),
            OssClient::OSS_CONTENT_TYPE => $request->input('mime_type'),
            OssClient::OSS_CALLBACK => json_encode([
                'callbackBodyType' => 'application/json',
                'callbackUrl' => route('storage:callback', [
                    'channel' => $resource->getChannel(),
                    'path' => base64_encode($resource->getPath()),
                ]),
                'callbackBody' => json_encode([
                    'jwt' => '${x:auth-token}',
                ]),
            ]),
            OssClient::OSS_CALLBACK_VAR => json_encode([
                'x:auth-token' => $this->guard()->login($user),
            ]),
        ];

        $url = $this->oss->signUrl(
            $this->configure['bucket'],
            $resource->getPath(),
            $this->configure['timeout'],
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

        return $this->metas[$resourceString] = new AliyunOss\FileMeta($this->insideOss, $resource, $this->configure['bucket']);
    }

    /**
     * Get file response.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param string|null $rule
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(ResourceInterface $resource, ?string $rule = null): Response
    {
        $url = $this->configure['domain'].'/'.$resource->getPath();
        if ($rule) {
            $url .= '?x-oss-process='.$rule;
        }
        if ($this->configure['acl'] === 'private') {
            $url = $this->oss->signUrl($this->configure['bucket'], $resource->getPath(), $this->configure['timeout'], 'GET', [
                OssClient::OSS_PROCESS => $rule,
            ]);
        }

        return new RedirectResponse($url, Response::HTTP_TEMPORARY_REDIRECT);
    }

    /**
     * Delete file.
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        $this->insideOss->deleteObject($this->configure['bucket'], $path);

        return true;
    }

    /**
     * Put a file.
     * @param string $path
     * @param mixed $contents
     * @return bool
     */
    public function put(string $path, $contents): bool
    {
        $this->insideOss->putObject($this->configure['bucket'], $path, $contents);

        return true;
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
