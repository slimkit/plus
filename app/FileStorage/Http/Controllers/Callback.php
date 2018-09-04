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

use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\FileStorage\Resource;
use Zhiyi\Plus\FileStorage\StorageInterface;

class Callback
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
        $this->storage = $storage;
    }

    /**
     * Callcack handler.
     * @param string $channel
     * @param string $path
     * @return \Illuminate\Http\JsonResponse;
     */
    public function __invoke(string $channel, string $path): JsonResponse
    {
        $resource = new Resource($channel, base64_decode($path));
        $this->storage->callback($resource);

        return new JsonResponse(['node' => (string) $resource], JsonResponse::HTTP_OK);
    }
}
