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

use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;
use Illuminate\Support\Facades\Route;
use SlimKit\PlusID\Web\Controllers as Web;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'plus-id'], function (RouteRegisterContract $route) {
    $route->any('/clients/{client}', Web\HomeController::class.'@index');

    // Home Router.
    // $route->get('/', Web\HomeController::class.'@index');

    // resolve
    // $route->get('/clients/{client}', Web\AuthController::class.'@resolve');

    // login
    // $route->any('/clients/{client}/login', Web\AuthController::class.'@login');
});

Route::group(['prefix' => 'api/v2'], function (RouteRegisterContract $api) {
    $api->group(['prefix' => '/plus-id'], function (RouteRegisterContract $api) {
        $api->group(['middleware' => 'auth:api'], function (RouteRegisterContract $api) {
            $api->get('/toShop/{client}', Web\ShopController::class.'@toShop');
        });
    });
});
