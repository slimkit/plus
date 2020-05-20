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

namespace Zhiyi\Plus\FileStorage\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Cache\Factory as FactoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Zhiyi\Plus\FileStorage\Resource;
use Zhiyi\Plus\FileStorage\StorageInterface;
use function Zhiyi\Plus\setting;

class Local extends Controller
{
    /**
     * File storage instance.
     * @var \Zhiyi\Plus\FileStorage\StorageInterface
     */
    protected $storage;

    /**
     * Create the controller instance.
     * @param \Zhiyi\Plus\FileStorage\StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this
            ->middleware('signed')
            ->only(['put']);
        $this
            ->middleware('auth:api')
            ->only(['put']);

        $this->storage = $storage;
    }

    /**
     * Get a file.
     * @param \Illuminate\Http\Request $request
     * @param string $channel
     * @param string $path
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function get(Request $request, string $channel, string $path): Response
    {
        $resource = new Resource($channel, base64_decode($path));

        return $this->storage->response($resource, $request->query('rule', null));
    }

    /**
     * Put a file.
     * @param \Illuminate\Http\Request $request
     * @param s\Illuminate\Contracts\Cache\Factory $cache
     * @param string $channel
     * @param string $path
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function put(Request $request, FactoryContract $cache, string $channel, string $path): JsonResponse
    {
        $signature = $request->query('signature');
        if ($cache->has($signature)) {
            throw new AccessDeniedHttpException('未授权的非法访问');
        }

        $contentHash = md5($content = $request->getContent());
        $hash = $request->header('x-plus-storage-hash');
        if ($hash !== $contentHash) {
            throw new UnprocessableEntityHttpException('Hash 校验失败');
        }

        $resource = new Resource($channel, base64_decode($path));
        if (! $this->storage->put($resource, $content)) {
            throw new HttpException(500, '储存文件失败');
        }

        $this->storage->callback($resource);
        $expiresAt = (new Carbon)->addSeconds(
            setting('file-storage', 'filesystems.local', ['timeout' => 3360])['timeout']
        );
        $cache->put($signature, 1, $expiresAt);
        $this->guard()->invalidate();

        return new JsonResponse(['node' => (string) $resource], Response::HTTP_CREATED);
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
