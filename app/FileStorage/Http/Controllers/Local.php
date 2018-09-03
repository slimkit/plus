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

namespace Zhiyi\Plus\FileStorage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use function Zhiyi\Plus\setting;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\FileStorage\Resource;
use Zhiyi\Plus\FileStorage\StorageInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Contracts\Cache\Factory as FactoryContract;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Local extends Controller
{
    protected $storage;

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

    public function get(Request $request, string $channel, string $path)
    {
        $resource = new Resource($channel, base64_decode($path));

        return $this->storage->response($resource, $request->query('rule', null));
    }

    public function put(Request $request, FactoryContract $cache, string $channel, string $path): Response
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
            throw new HttpException('储存文件失败');
        }

        $this->storage->callback($resource);
        $expiresAt = (new Carbon)->addHours(
            setting('core', 'file:put-signature-expires-at', 1)
        );
        $cache->put($signature, 1, $expiresAt);
        $this->guard()->invalidate();

        return new Response('', Response::HTTP_NO_CONTENT);
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
