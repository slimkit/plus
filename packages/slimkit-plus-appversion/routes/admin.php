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
use Slimkit\PlusAppversion\Admin\Controllers as Admin;

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

Route::group(['prefix' => 'admin/plus-appversion'], function (RouteRegisterContract $route) {
    // Home router.
    $route->get('/', Admin\HomeController::class.'@home')->name('plus-appversion:admin-home');
    $route->get('/current', Admin\HomeController::class.'@currentVersion')->name('plus-appversion:current-version');
    $route->get('/versions', Admin\HomeController::class.'@index')->name('plus-appversion:admin-detail');
    $route->post('/', Admin\HomeController::class.'@store');
    $route->delete('/{clientVersion}', Admin\HomeController::class.'@delete');
    $route->post('/upload', Admin\HomeController::class.'@storage')->name('plus-appversion:admin-upload');
    $route->get('/status', Admin\HomeController::class.'@status')->name('plus-appversion:admin-status');
    $route->patch('/status', Admin\HomeController::class.'@update')->name('plus-appversion:admin-status-update');
    $route->post('/download-manage', Admin\DownloadController::class);
});
