<?php

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
use Zhiyi\Plus\Packages\Installer\Middleware;
use Zhiyi\Plus\Packages\Installer\Controllers;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

Route::view('/', 'installer');

Route::post('/password', Controllers\InstallController::class.'@verifyPassword');
Route::get('/license', Controllers\InstallController::class.'@license');
Route::group(['middleware' => Middleware\VerifyInstallationPassword::class], function (RouteRegisterContract $route) {
    $route->post('/check', Controllers\InstallController::class.'@check');
    $route->post('/info', Controllers\InstallController::class.'@getInfo');
    $route->put('/info', Controllers\InstallController::class.'@setInfo');
});
