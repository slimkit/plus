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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed;

use Illuminate\Contracts\Routing\Registrar as RouterContract;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(RouterContract $router)
    {
        $this->router = $router;
    }

    /**
     * Register all.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function all()
    {
        $this->forAdmin();
        $this->forApi2();
    }

    /**
     * Register admin routes.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function forAdmin()
    {
        $this->router->group([
            'middleware' => ['web', 'auth', 'admin'],
            'prefix' => '/feed/admin',
            'namespace' => 'Zhiyi\\Component\\ZhiyiPlus\\PlusComponentFeed\\AdminControllers',
        ], dirname(__DIR__).'/routes/admin.php');
        $this->router->group([
            'middleware' => 'web',
            'prefix' => '/feed/admin',
        ], dirname(__DIR__).'/routes/new-admin.php');
    }

    /**
     * Register api 2 routes.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function forApi2()
    {
        $this->router->group([
            'middleware' => ['api'],
            'prefix' => '/api/v2',
            'namespace' => 'Zhiyi\\Component\\ZhiyiPlus\\PlusComponentFeed\\API2',
        ], dirname(__DIR__).'/routes/api2.php');
    }
}
