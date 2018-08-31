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

namespace Zhiyi\Plus\FileStorage\Http;

use Illuminate\Contracts\Routing\Registrar as RegistrarContract;

class MakeRoutes
{
    protected $router;

    public function __construct(RegistrarContract $router)
    {
        $this->router = $router;
    }

    public function register(): void
    {
        $this->registerLocalFilesystemRoutes();
        $this->registerChannelCallbackRoutes();
        $this->registerCreateTaskRoutes();
    }

    protected function registerLocalFilesystemRoutes(): void
    {
        $this->router->group(['prefix' => 'storage'], function (RegistrarContract $router) {
            $router
                ->get('{channel}:{path}', Controllers\Local::class.'@get')
                ->name('storage:local-get');
            $router
                ->put('{channel}:{path}', Controllers\Local::class.'@put')
                ->name('storage:local-put');
        });
    }

    protected function registerChannelCallbackRoutes(): void
    {
        $this->router->group(['prefix' => 'api/v2'], function (RegistrarContract $router) {
            $router
                ->post('storage/{channel}:{path}', Controllers\Callback::class)
                ->name('storage:callback');
        });
    }

    protected function registerCreateTaskRoutes(): void
    {
        $this->router->group(['prefix' => 'api/v2'], function (RegistrarContract $router) {
            $router
                ->post('storage', Controllers\CreateTask::class)
                ->name('storage:create-task');
        });
    }
}
