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

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Zhiyi\Plus\FileStorage\StorageInterface;

class CreateTask extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Create a upload task.
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\FileStorage\StorageInterface $storage
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, StorageInterface $storage)
    {
        $task = $storage->createTask($request);

        return new JsonResponse([
            'uri' => $task->getUri(),
            'method' => $task->getMethod(),
            'headers' => $task->getHeaders(),
            'form' => $task->getForm(),
            'file_key' => $task->getFileKey(),
            'node' => $task->getNode(),
        ], JsonResponse::HTTP_CREATED);
    }
}
