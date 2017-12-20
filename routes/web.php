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
 * +----------------------------------------------------------------------+
 */

Route::get('/', 'HomeController@welcome');
Route::get('/auth/login', 'Auth\\LoginController@showLoginForm')->name('login');
Route::post('/auth/login', 'Auth\\LoginController@login');
Route::any('auth/logout', 'Auth\\LoginController@logout')->name('logout');

Route::prefix('admin')
    ->namespace('Admin')
    ->group(base_path('routes/admin.php'));
