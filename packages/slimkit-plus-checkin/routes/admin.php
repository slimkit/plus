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
use SlimKit\PlusCheckIn\Admin\Controllers as Admin;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => 'slimkit/checkin',
    'middleware' => 'ability:admin: checkin config',
], function (RouteRegisterContract $route) {

    // Home router.
    $route->get('/', Admin\HomeController::class.'@index')->name('checkin:admin-home');

    // Store
    $route->put('/', Admin\HomeController::class.'@store')->name('checkin:store-config');
});
