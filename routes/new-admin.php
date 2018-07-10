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

use Illuminate\Support\Facades\Route;
use Zhiyi\Plus\Admin\Controllers as AdminControllers;
use Illuminate\Contracts\Routing\Registrar as RouteContract;

Route::middleware(['auth:web', 'admin'])->prefix('admin')->group(function (RouteContract $route) {
    $route->get('im/helper-user', AdminControllers\ImHelperUserController::class.'@fetch');
    $route->put('im/helper-user', AdminControllers\ImHelperUserController::class.'@update');
    $route->get('trashed-users', AdminControllers\UserTrashedController::class.'@index');
    $route->delete('trashed-users/{user}', AdminControllers\UserTrashedController::class.'@restore');
    $route->get('about-us', AdminControllers\AboutUsController::class.'@show');
    $route->patch('about-us', AdminControllers\AboutUsController::class.'@store');
});
