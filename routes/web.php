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

if (! Route::has('home')) {
    /**
     * Get front “/” route.
     *
     * @var \Illuminate\Routing\Route
     */
    $route = array_get(Route::getRoutes()->get('GET'), '/');

    // Not defined "/" route,
    // Create a default "/" route.
    if (! $route) {
        $route = Route::get('/', 'HomeController@welcome');
    }

    // Set "/" route name as "home"
    $route->name('home');
}

if (! Route::has('login')) {
    Route::get('/auth/login', 'Auth\\LoginController@showLoginForm')->name('login');
}

if (! Route::has('logout')) {
    Route::any('auth/logout', 'Auth\\LoginController@logout')->name('logout');
}

if (! Route::has('redirect')) {
    Route::get('/redirect', 'HomeController@redirect')->name('redirect');
}

Route::post('/auth/login', 'Auth\\LoginController@login');

Route::prefix('admin')
    ->namespace('Admin')
    ->group(base_path('routes/admin.php'));
