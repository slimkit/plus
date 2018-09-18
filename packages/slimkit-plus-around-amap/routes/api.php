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
use SlimKit\PlusAroundAmap\API\Controllers as API;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'api/v2'], function (RouteRegisterContract $api) {

    // Test route.
    // @ANY /api/around-amap
    $api->group(['prefix' => 'around-amap'], function (RouteRegisterContract $api) {
        $api->get('/', API\HomeController::class.'@getArounds');
        $api->get('/geo', API\HomeController::class.'@getgeo');
        $api->group(['middleware' => 'auth:api'], function (RouteRegisterContract $api) {
            $api->post('/', API\HomeController::class.'@index');
            $api->patch('/', API\HomeController::class.'@update');
            $api->delete('/', API\HomeController::class.'@delete');
            $api->get('/source', API\HomeController::class.'@getMyAmapId');
        });
    });
});
