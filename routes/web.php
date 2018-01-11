<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

// if (! Route::getRoutes()->hasNamedRoute('home')) {
    Route::get('/', 'HomeController@welcome')->name('home');
// }

if (! Route::getRoutes()->hasNamedRoute('login')) {
    Route::get('/auth/login', 'Auth\\LoginController@showLoginForm')->name('login');
}

if (! Route::getRoutes()->hasNamedRoute('logout')) {
    Route::any('auth/logout', 'Auth\\LoginController@logout')->name('logout');
}

Route::post('/auth/login', 'Auth\\LoginController@login');

Route::prefix('admin')
    ->namespace('Admin')
    ->group(base_path('routes/admin.php'));
