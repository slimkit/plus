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

namespace Zhiyi\Plus\FileStorage\Http;

use Illuminate\Contracts\Routing\Registrar as RegistrarContract;

class MakeRoutes
{
    /**
     * The router instance.
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create the maker instance.
     * @param \Illuminate\Contracts\Routing\Registrar $router
     */
    public function __construct(RegistrarContract $router)
    {
        $this->router = $router;
    }

    /**
     * The routes resister.
     * @return void
     */
    public function register(): void
    {
        $this->registerLocalFilesystemRoutes();
        $this->registerChannelCallbackRoutes();
        $this->registerCreateTaskRoutes();
    }

    /**
     * Register local filesystem routes.
     * @return void
     */
    protected function registerLocalFilesystemRoutes(): void
    {
        $this->router->group(['prefix' => 'storage'], function (RegistrarContract $router) {
            $router
                ->get('{channel}:{path}', Controllers\Local::class.'@get')
                ->name('storage:get');
            $router
                ->put('{channel}:{path}', Controllers\Local::class.'@put')
                ->name('storage:local-put');
        });
    }

    /**
     * Register channel callback routes.
     * @return void
     */
    protected function registerChannelCallbackRoutes(): void
    {
        $this->router->group(['prefix' => 'api/v2'], function (RegistrarContract $router) {
            $router
                ->post('storage/{channel}:{path}', Controllers\Callback::class)
                ->name('storage:callback');
        });
    }

    /**
     * Register create a upload task routes.
     * @return void
     */
    protected function registerCreateTaskRoutes(): void
    {
        $this->router->group(['prefix' => 'api/v2'], function (RegistrarContract $router) {
            $router
                ->post('storage', Controllers\CreateTask::class)
                ->name('storage:create-task');
        });
    }
}
